<?php


namespace App\Controllers;


use App\Components\SessionHelpers;
use App\Models\Currency;
use App\Models\Product;
use Psr\Container\ContainerInterface;

class CurrencyController
{
    private $c;

    private $currency;

    public function __construct(ContainerInterface $container, Currency $currency)
    {
        $this->c = $container;
        $this->currency = $currency;
    }

    public function currencyUpdate ($request,$response){

        $content = file_get_contents("http://www.nbrb.by/api/exrates/rates?periodicity=0");
        $content=json_decode($content,TRUE);

        foreach ($content as $currenciesAttribute => $value){

            $data[]=['name'=>$value['Cur_Abbreviation'],
                     'value'=>$value['Cur_OfficialRate']];
        }
            Currency::insert($data);
        return 'Валюта успешно добавлена';
    }
    /**
     * @param $request \Slim\Http\Request
     * @param $response
     * @return mixed
     */
    public function switchCurrency($request,$response){

        $currencyName= $request->getParsedBody();
        if (empty($currencyName['name'])){
            return $response->withRedirect('/shop');
        }
        $currency = $this->currency->where('name', $currencyName)->first();
        if(!empty($currency)){
            switchCurrency($currency['name'],$currency['value']);
        }
        return $response->withRedirect('/shop');


    }
}