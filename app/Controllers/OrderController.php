<?php


namespace App\Controllers;


use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Psr\Container\ContainerInterface;

class OrderController extends Controller
{
    private $c;
    private $order;

    public function __construct(ContainerInterface $container, Order $order)
    {
        $this->c = $container;
        $this->order = $order;
    }

    public function showOrder($request,$response)
    {

        $orderNumber = $request->getAttribute('orderNumber');
        $order = $this->order->with(['products' => function ($query) {
            $query->withPivot('qty');
        }])->where('order_number', $orderNumber)->first();
        //404
        if (empty($order)) {
            $this->abort404($response);
        }

        return $this->c->view->render($response, 'order.html.twig', [
            'order' => $order,
            'products' => $order->products,
        ]);

    }



}