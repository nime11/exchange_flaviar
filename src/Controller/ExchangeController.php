<?php

namespace App\Controller;

use App\Entity\ExchangeData;
use App\Repository\ExchangeDataRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExchangeController extends AbstractController
{

    /**
     * @Route("/exchange", name="app_exchange")
     */
    public function index( ManagerRegistry $doctrine): Response
    {
        
        return $this->render('exchange/index.html.twig', [
            'controller_name' => 'ExchangeController',
        ]);
    }

    /**
     * @Route("/ajax_route/{action}", name="ajax_route", methods={"POST"})
     */
    public function ajaxAction(Request $request, ManagerRegistry $doctrine)
    {
        // Get the data from the request
        $data = $request->request->get('data');
        $exDRepository= new ExchangeDataRepository($doctrine);
        // Do something with the data
        $action = $request->attributes->get('action');
      
        if ($action === 'addYearData') {
            
            $exchangeData =$this->getExchagneDataYear();
            foreach($exchangeData as $key=>$data){
                $exData = new ExchangeData();
                $exData->setDate(new DateTime($key));
                $exData->setCurrencyFrom('EUR');
                $exData->setCurrencyTo('USD');
                $exData->setValueFrom('1');
                $exData->setValueTo($data['USD']);   

                $this->createExchange($doctrine, $exData);
            }
            $dataR=  'added';
        } elseif($action === 'getYearData'){            
            $dataR = [];
            $yeardata=  $exDRepository->getYearData('2020');
            if($yeardata){
                foreach($yeardata as $val){
                    $dataR[] =  ['date' =>  $val->getDate()->format('Y-m-d'),'value' =>$val->getValueTo()];
                }
            }           
        } elseif($action === 'getDayData'){
            $dayData = $this->getExchagneDataDay(new DateTime($data));
            $exData = new ExchangeData();
            $exData->setDate(new DateTime($data));
            $exData->setCurrencyFrom('EUR');
            $exData->setCurrencyTo('USD');
            $exData->setValueFrom('1');
            $exData->setValueTo($dayData);   

            $id = $this->createExchange($doctrine, $exData);
            $dateT = new DateTime($data);
            
            $dataR=  ['date' => $dateT->format('Y-m-d'),'value' =>$dayData];
        } else{
            $dataR=  '';
        }

                   // Return a JSON response
        return new JsonResponse($dataR);
    }

    public function createExchange(ManagerRegistry 
    $doctrine, $exchangeData): Response
    {
        $entityManeger = $doctrine->getManager();
        $entityManeger->persist($exchangeData);
        $entityManeger->flush();
        return new Response($exchangeData->getID());
    }

    public function getExchagneDataYear($year = '2020') {
        // Get data for year 
        $client = HttpClient::create();
        $query = [
            'start_date' => '2020-01-01',
            'end_date' => '2020-12-31',
            'base' => 'EUR',
            'symbols' => 'USD',
            'amount' => '1',
        ];
        
        $response = $client->request('GET',  'https://api.exchangerate.host/timeseries', ['query' => $query]);
        
        if($response->getStatusCode()){
            $data = $response->toArray();;
            if(isset($data['rates'])){
                return $data['rates'];    
            }  else{
                return [];
            }          
        } else {
            return [];
        }        
    }
    public function getExchagneDataDay(DateTime $date): float {
        $datestring = $date->format('Y-m-d');
        $client = HttpClient::create();
        $response = $client->request('GET', "https://api.exchangerate.host/$datestring &base=EUR&symbols=USD&amount=1");
        if($response->getStatusCode()){
            $data = $response->toArray();;
            if(isset($data['rates']['USD'])){
                return $data['rates']['USD'];    
            }  else{
                return 0;
            }  
        } else {
            return 0;
        }
    }
}
