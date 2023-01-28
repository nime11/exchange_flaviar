<?php

namespace App\Controller;

use App\Entity\ExchangeData;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class ExchangeController extends AbstractController
{
    /**
     * @Route("/exchange", name="app_exchange")
     */
    public function index(): Response
    {
        return $this->render('exchange/index.html.twig', [
            'controller_name' => 'ExchangeController',
        ]);
    }

    public function createExchange(ManagerRegistry $doctrine, $exchangeData): Response
    {
        $entityManeger = $doctrine->getManager();
        $entityManeger->persist($exchangeData);
        $entityManeger->flush();
        return new Response($exchangeData->getID());
    }

    public function getExchagneDataYear($year = '2020'){
        $client = HttpClient::create();
        $response = $client->request('GET', "https://api.exchangerate.host/timeseries?start_date=$year-01-01&end_date=$year-12-31&base=EUR&symbols=USD&amount=1");
        if($response->getStatusCode()){
            $data = json_decode($response->getContent());
        } else {
            return false;
        }        
    }
    public function getExchagneDataDay(DateTime $date){
        $datestring = $date->format('Y-m-d');
        $client = HttpClient::create();
        $response = $client->request('GET', "https://api.exchangerate.host/$datestring &base=EUR&symbols=USD&amount=1");
        if($response->getStatusCode()){
            $data = json_decode($response->getContent());
        } else {
            return false;
        }
    }
}
