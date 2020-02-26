<?php


namespace App\Controllers;

use App\Models\Category;
use Psr\Container\ContainerInterface;
use App\Models\User;
use Slim\Http\Response;
use Exception;



class RegisterController extends controller
{
    const SALT = 'evqejnvq';
    private $c;

    private $user;

    public function __construct(ContainerInterface $container, User $user)
    {
        $this->c = $container;
        $this->user = $user;

    }


    public function register($request, $response)
    {
        return $this->c->view->render($response, 'signUp.html.twig');
    }
    /**
     * @param $request \Slim\Http\Request
     * @param $response Response
     * @return mixed
     * @throws Exception
     */
    public function storeUser($request,$response){

        $data = $request->getParsedBody();
        $data['password'] = crypt($data['password'],self::SALT);
        User::create($data);
        return $response->withRedirect('/');


    }
}
//public function showOrder($request,$response){
//
//    $orderNumber = $request->getAttribute('orderNumber');
//    $order = $this->order->with(['products' => function($query){
////            $query->
//    }])->where('order_number', $orderNumber)->first();
//
//    $products= $order->products;
//
//
//    return $this->c->view->render($response, 'order.html.twig', [
//        'order' => $order,
//        'products'=> $products,
//    ]);
//
//}