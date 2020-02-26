<?php


namespace App\Controllers;


use App\Components\SessionHelpers;
use App\Models\Order;
use App\Models\Product;
use Exception;
use http\Env\Request;
use Psr\Container\ContainerInterface;
use Slim\Http\Response;
use App\Components\Mail;

class CartController
{
    private $c;
    private $product;

    public function __construct(ContainerInterface $container, Product $product)
    {
        $this->c = $container;
        $this->product = $product;
    }

    /**
     * @param $request \Slim\Http\Request
     * @param $response Response
     * @return mixed
     */
    public function AddToCart($request, $response)
    {

        $id = (int)$request->getAttribute('id');
        $product = $this->product->where('id', $id)->first();
        $this->c->cart->add($id, 1, $product['price']);
        return $response->withRedirect('/shop');
    }

    public function showCart($request, $response)
    {
        if (!empty ($this->c->cart->get())) {
            $cartProducts = $this->c->cart->get();
            $ids = array_column($cartProducts, 'id');
            $products = $this->product->whereIn('id', $ids)->get();
            return $this->c->view->render($response, 'cart.html.twig', [
                'products' => $products,
            ]);
        } else {
            return $this->c->view->render($response, 'cart.html.twig', [
                'cartEmpty' => 'Корзина пуста'
            ]);
        }
    }

    /**
     * @param $request \Slim\Http\Request
     * @param $response Response
     * @return mixed
     */
    public function delete($request, $response)
    {

        $id = (int)$request->getAttribute('id');
        $this->c->cart->delete($id);
        return $response->withRedirect('/cart');

    }

    /**
     * @param $request \Slim\Http\Request
     * @param $response Response
     * @return mixed
     * @throws Exception
     */
    public function edit($request, $response)
    {

        $id = (int)$request->getAttribute('id');
        $action = $request->getAttribute('action');
        $qty = $request->getAttribute('qty');
        $this->c->cart->edit($id, $qty, $action);
        return $response->withRedirect('/cart');

    }

    /**
     * @param $request \Slim\Http\Request
     * @param $response Response
     * @return mixed
     * @throws Exception
     */
    public function deleteCart($request, $response)
    {

        $this->c->cart->destroy();
        return $response->withRedirect('/cart');
    }

    /**
     * @param $request \Slim\Http\Request
     * @param $response
     * @return mixed
     */
    public function storeOrder($request, $response)
    {
        $data = $request->getParsedBody();
        $data['total_price'] = round($this->c->cart->totalPrice()*$_SESSION['currency']['value']);
        $data['order_number'] = time();
        $email = $data['email'];

        if(!empty($_SESSION['user'])){
            $data['user_id']=$_SESSION['user']['id'];
        }

        $data = Order::create($data);
        $cartProducts = $this->c->cart->get();
        $data->products()->attach(array_map(function ($product) {
            return ['qty' => $product['qty']];
        }, $cartProducts));
        SessionHelpers::destroyByKey('cart');

        $textMail = getEmailMessageOrder($data);

        $this->c->Mail->sendMail($email,$textMail);
        return $response->withRedirect('/order/' . $data['order_number']);
    }

    public function test($request,$response){


        dd($_SESSION);
    }

}