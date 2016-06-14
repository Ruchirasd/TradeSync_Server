<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Stock;
use Proxies\__CG__\AppBundle\Entity\Exchange;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StockController extends Controller
{
    //This is to query all the stocks related to given parameter(exchanges)
    public function queryAction(Request $request,$stockExId)
    {
        $stock = $this->getDoctrine()
            ->getRepository('AppBundle:Stock')
            ->findBy(
                array(
                    'exchange' => $stockExId
                )
            );

        //add all stocks to a list
        $stockList=[];
        foreach($stock as $stk){
            $stock = new \stdClass();
            $stock->stock_code = $stk->getStockCode();
            $stock->exchange_id = $stk->getExchange()->getId();
            $stock->id = $stk->getId();
            $stock->last_price = $stk->getLastPrice();
            $stockList[]=$stock;

        }

        //generate the response
        $response = new Response();

        $response->setContent(json_encode($stockList));
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    public function persistStock($stockExCode, $stockCode, $lastPrice)
    {

        $stock = new Stock();
        $stock->setExchange($stockExCode);
        $stock->setStockCode($stockCode);
        $stock->setLastPrice($lastPrice);

        $em = $this->getDoctrine()->getManager();

        //save the user
        $em->persist($stock);

        // flush the created user to the database
        $em->flush();

        //create the response
        $response = new Response();

        $response->setContent('Saved stock with id '.$stock->getId());
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
