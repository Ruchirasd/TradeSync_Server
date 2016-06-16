<?php
/**
 * Created by PhpStorm.
 * User: Ruchira
 * Date: 5/21/2016
 * Time: 8:55 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Stock;
use AppBundle\Entity\Exchange;
use AppBundle\Entity\StockHistory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;

class CrawlerController extends Controller
{
    public function grabAction($stockExCode){
        switch(strtoupper($stockExCode)){
            case "CSE": $this->grabCSE(); break;
            case "LME": $this->grabLME(); break;
        }

        return new Response("OK");
    }

    private function getStockId($stockCode)
    {
        $stock = $this->getDoctrine()
            ->getRepository('AppBundle:Stock')
            ->findOneBy(
                array(
                    'stockCode' => $stockCode
                )
            );
        return $stock->getId();
    }

    private function grabLME(){
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, "http://www.lme.com");
        curl_setopt($c, CURLOPT_TRANSFERTEXT, true);
        $contents = curl_exec($c);
        curl_close($c);
        $contents = explode("<h2 class='main-heading'>Official Prices</h2>",$contents)[1];
        $contents = explode('<div class="con" id="three-col-footer-feature">',$contents)[0];
        $contents = explode('<tr>',$contents);

        for($s=0;$s<count($contents);$s++){
            echo $contents[$s];
        }


        //echo $contents;
    }

    private function getStockExId($stockExCode)
    {
        $stock = $this->getDoctrine()
            ->getRepository('AppBundle:Exchange')
            ->findOneBy(
                array(
                    'code' => $stockExCode
                )
            );
        return $stock->getId();
    }

    private function grabCSE(){

        $data=$this->grabData();

        //--------------get exchange
        $ex = $this->getDoctrine()
            ->getRepository('AppBundle:Exchange')
            ->findBy(
                array(
                    'code' => "CSE"
                )
            )[0];
        //---------------

        $index=18; //to drop the headings in the csv file start at index 18

        while(true){
            if($index+4>count($data))
                break;
            echo $data[$index];//stock code
            echo $data[$index+4]; //price

            /*------------------------------------
            persist stock */



            $stock = $this->getDoctrine()
                ->getRepository('AppBundle:Stock')
                ->findOneBy(
                    array(
                        'stockCode' => $data[$index]
                    )
                );
            $em = $this->getDoctrine()->getManager();

            if(empty($stock)){
                $stock = new Stock();
                $stock->setExchange($ex);
                $stock->setStockCode($data[$index]);
            }else{
                $history = $this->getDoctrine()
                    ->getRepository('AppBundle:StockHistory')
                    ->findOneBy(
                        array(
                            'date' => $data[$index+3],
                            'stockId' => $this->getStockId($data[$index])
                        )
                    );
                if(empty($history)){
                    $history = new StockHistory();
                    $history->setStockId($this->getStockId($data[$index]));
                    $history->setDate($data[$index+3]);
                }

                $history->setMaxPrice($data[$index+8]);
                $history->setMinPrice($data[$index+9]);
                $em->persist($history);
            }


            // check if new day


            // if already exist update details
            $stock->setName($data[$index+1]);
            $stock->setLastPrice($data[$index+4]);
            $stock->setStatus(0);


            $em->persist($stock);


            $em->flush();
            //-----------------------------------

            $index+=17;
        }

    }


    public function grabData(){
        //$data = file_get_contents("https://www.cse.lk/trade_summary_report.do?reportType=CSV");
        $data = file_get_contents("http://wearetrying.info/trade_summary_13.csv");
        $data = explode(",",$data);
        return $data;
    }

    public function updateAction()
    {

    }

    public function autoUpdate(){
        $dispatcher= new EventDispatcher();
    }

}