<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Stock;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    //Controls the user registration
    //take relevant details as parameters
    public function registerAction(Request $request,$name,$email,$password)
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        $em = $this->getDoctrine()->getManager();
        try {
            //save the user
            $em->persist($user);

            // flush the created user to the database
            $em->flush();
        }
        catch(\Exception $e)
        {
            $user= null;
        }
        //create the response
        $response = new Response();

        if($user== null){
            $response->setContent('ERROR');
        }else{
            $content = new \StdClass();
            $content->id = $user->getId();
            $content->email = $user->getEmail();
            $content->name = $user->getName();
            $response->setContent(json_encode($content));
        }


        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;


    }

    //Controls the stock subscription
    //take relevant details as parameters
    public function subscribeAction(Request $request,$userId, $stockExCode, $stockCode)
    {
        $em = $this->getDoctrine()->getManager();

        //get the stock relevant to given parameters
        $stock = $this->getDoctrine()
            ->getRepository("AppBundle:Stock")
            ->getStock($stockCode,$stockExCode);

        //get the user with given id
        $user = $this->getDoctrine()
            ->getRepository("AppBundle:User")
            ->findOneBy(
                array(
                    'id' => $userId
                )
            );

        //set status in the stock
        $status= $stock->getStatus();
        if($status==0)
            $stock->setStatus(1);


        //add stock to the user and save in the database
        $user->addStock($stock);
        $em->persist($user);
        $em->flush();


        //generate the response
        $response = new Response();

        $response->setContent('OK');
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    //Controls the stock unsubscription
    //take relevant details as parameters
    public function unsubscribeAction(Request $request,$userId, $stockExCode, $stockCode)
    {
        $em = $this->getDoctrine()->getManager();

        //get the stock relevant to given parameters
        $stock = $this->getDoctrine()
            ->getRepository("AppBundle:Stock")
            ->getStock($stockCode,$stockExCode);

        //get the user with given id
        $user = $this->getDoctrine()
            ->getRepository("AppBundle:User")
            ->findOneBy(
                array(
                    'id' => $userId
                )
            );

        //remove stock from the user which is saved in the database
        $stockId=$stock->getId();
        $stockList=$this->getDoctrine()
            ->getRepository("AppBundle:Stock")
            ->checkUsers($stockId);
        if(count($stockList)==1){
            $stock->setStatus(0);
        }
        $user->removeStock($stock);
        $em->persist($user);
        $em->flush();

        //generate the response
        $response = new Response();

        $response->setContent('OK');
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    //controls the querying of user details
    public function queryAction(Request $request,$userId)
    {
        //get the user with given id
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        //generate response with showing up the user details
        $response = new Response();

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:User');
        $repo = $this->getDoctrine()
            ->getRepository('AppBundle:StockHistory');


        $userStoks=$repository->getUserDetails($userId);
        $arr = [];

        foreach($userStoks as $stock){


            $history=$repo->getHistory($stock['stockCode']);
            $stock=array_merge($stock,['history'=>$history]);
            array_push($arr,$stock);
        }
        $response->setContent(json_encode($arr));
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;

    }


    public function validateUserAction($email,$password){
        $user= $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(
                array(
                    'email' => $email,
                    'password' => $password
                )
            );
        $response = new Response();

        if($user== null){
            $response->setContent('ERROR');
        }else{
            $content = new \StdClass();
            $content->id = $user->getId();
            $content->email = $user->getEmail();
            $content->name = $user->getName();
            $response->setContent(json_encode($content));
        }


        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;

    }




}
