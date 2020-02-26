<?php


namespace App\Controllers;


use App\Models\Category;
use App\Models\Product;
use Psr\Container\ContainerInterface;

class CategoryController extends Controller
{
    private $c;

    private $category;

    public function __construct(ContainerInterface $container, Category $category)
    {
        $this->c = $container;
        $this->category = $category;
    }

    public function category($request, $response)
    {

        return $this->c->view->render($response, 'category.html.twig');

    }

    /**
     * @param $request
     * @param $response
     * @return string
     */
    public function storeCategory($request, $response)
    {

        $data = $request->getParsedBody();
        category::create($data);
        return 'Category: ' . $data['name'] . ' successfully added';

    }

    public function showCategory($request, $response)
    {

        return $this->c->view->render($response, 'showCategory.html.twig', [
            'categories' => $data = $this->category->all(),
        ]);
    }

    public function categoryProduct($request, $response)
    {


        $name = $request->getAttribute('name');
        $category = Category::with('products')->where('name', $name)->first();

        if (empty($category)) {
            $this->abort404($response);
        }


        return $this->c->view->render($response, 'categoryProduct.html.twig', [
            'products' => $category->products,
        ]);
    }
}