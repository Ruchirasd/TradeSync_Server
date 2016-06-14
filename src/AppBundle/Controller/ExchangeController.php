<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Exchange;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends Controller
{
    //This is to query all the exchanges that the application support
    public function queryAction(Request $request)
    {
        $exchange = $this->getDoctrine()
            ->getRepository('AppBundle:Exchange')
            ->findAll();
        $exList=[];

        //add all the exchanges to a list
        foreach($exchange as $ex){
            $exchange = new \stdClass();
            $exchange->code = $ex->getCode();
            $exchange->name = $ex->getName();
            $exchange->id = $ex->getId();
            $exchange->country = $ex->getCountry();
            $exList[]=$exchange;
        }

        //create the response
        $response = new Response();

        $response->setContent(json_encode($exList));
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    public function persistExchangeAction($name, $code, $country)
    {
        $ex = new Exchange();
        $ex->setName($name);
        $ex->setCode($code);
        $ex->setCountry($country);

        $em = $this->getDoctrine()->getManager();

        //save the user
        $em->persist($ex);

        // flush the created user to the database
        $em->flush();

        //create the response
        $response = new Response();

        $response->setContent('Saved ex with id '.$ex->getId());
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;

    }
}
