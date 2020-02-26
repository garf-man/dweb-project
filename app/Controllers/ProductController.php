<?php

namespace App\Controllers;


use App\Components\SessionFacade;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Psr\Container\ContainerInterface;
use Slim\Http\UploadedFile;
use Slim\Http\Response;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductController extends Controller
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
     * @param $response
     * @return mixed
     */
    public function index($request, $response,$args)
    {
        $qtyShowItems = 5;
        $getParams = $request->getParams();
        if (empty($getParams['id'])){
            $getParams['id'] = 1;
        }
        $idPage = $getParams['id'];




        if (!empty($getParams['min_price'])) {
            dd('errors');
            $products = $this->product->where('price','>=', $getParams['min_price']);
        }

       // if (!empty($getParams['max_price'])) {
       //     $products->where('price','<=', $getParams['max_price']);
      //  }
      //  dd($products->count());





        $products = $this->product->limit($qtyShowItems);


        dd($products->count());
//        if (!empty($getParams['min_price'] && !empty($getParams['max_price'] ))) {
//
//            $products = $this->product->whereBetween('price', [$getParams['min_price'], $getParams['max_price']])->get();
//
//            $count = $products->count();
//            $products = $this->product->skip(($idPage-1)*$qtyShowItems)
//                                      ->whereBetween('price', [$getParams['min_price'], $getParams['max_price']])
//                                      ->limit($qtyShowItems)->get();
//        }   else {
//            $products = $this->product->get();
//            $count = $products->count();
//            $products = $this->product->skip(($idPage-1)*$qtyShowItems)
//                                      ->limit($qtyShowItems)->get();
//        }
//
//        $qtyPage = ceil($count / $qtyShowItems);


        if($idPage < 1 or $idPage > $qtyPage){
            $this->abort404($response);
        }

        return $this->c->view->render($response, 'shop.html.twig', [
            'products' => $products,
            'qtyPage' => $qtyPage,
        ]);
    }

    /**
     * @param $request \Slim\Http\Request
     * @param $response
     * @return mixed
     */
    public function show($request, $response)
    {

        $id = $request->getAttribute('id');
        $product = $this->product->where('id', $id)->first();

        if (empty($product)) {
            $this->abort404($response);
        }


        return $this->c->view->render($response, 'single.html.twig', [
            'products' => $product,
        ]);
    }


    public function add($request, $response)
    {

        return $this->c->view->render($response, 'add.html.twig', [
            'categories' => $data = category::all(),
        ]);


    }

    public function storeProduct($request, $response)
    {

        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        $filename = uploadRequestFile($uploadedFiles);
        $data['image'] = $filename;
        product::create($data);
        return 'Product: ' . $data['name'] . ' successfully added';
    }


    public function home($request, $response)
    {
        return $this->c->view->render($response, 'index.html.twig', [
        ]);
    }

}