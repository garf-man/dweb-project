<?php


use App\Components\Cart;
use App\Components\Mail;
use App\Components\SessionHelpers;
use App\Controllers\CartController;
use App\Controllers\CurrencyController;
use App\Controllers\LoginController;
use App\Controllers\OrderController;
use App\Controllers\ProductController;
use App\Controllers\CategoryController;
use App\Controllers\RegisterController;
use App\Controllers\UserController;
use App\Models\User;
use Twig\TwigFunction;


$container = $app->getContainer();


$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__.'/../resources/views/site', [
        'cache' => false,
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));
    $view->getEnvironment()
        ->addGlobal("cart",$container->get('cart') );

    $view->getEnvironment()
        ->addGlobal('sessionHelpers', $container->get('sessionHelpers') );

    $view->getEnvironment()
        ->addGlobal('session', $_SESSION);

    $function = new \Twig\TwigFunction('fn', function () {
        $args = func_get_args();
        $functionName = array_shift($args);
        return call_user_func_array($functionName, $args);
    });
    $view->getEnvironment()->addFunction($function);
    return $view;
};
$container['cart'] = function($container) {
     return  new Cart;
};
$container['sessionHelpers'] = function($container) {
    return  new sessionHelpers;
};

$container['Mail'] = function($container) {

    $config = getMailConfig();
    return  new Mail($config);
};





//container for Database Eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function ($container) use($capsule) {
    return $capsule;
};




$container[ProductController::class] = function($c) {
    return new ProductController($c, new \App\Models\Product([]));
};
$container[CategoryController::class] = function($c) {
    return new CategoryController($c, new \App\Models\Category([]));
};

//$container[RegisterController::class] = function($c) {
//    return new RegisterController($c,  new \App\models\User([]));
//};

$container[LoginController::class] = function($c) {
    return new LoginController($c, new \App\Models\User([]));
};


$container[RegisterController::class] = function($c) {
    return new RegisterController($c, new \App\Models\User([]));
};

$container[CartController::class] = function($c) {
    return new CartController($c, new \App\Models\Product([]));
};

$container[OrderController::class] = function($c) {
    return new OrderController($c, new \App\Models\Order([]));
};

$container[UserController::class] = function($c) {
    return new UserController($c, new \App\Models\User([]));
};

$container[CurrencyController::class] = function($c) {
    return new CurrencyController($c, new \App\Models\Currency([]));
};
//$container[TestController::class] = function ($c) {
//    $view = $c->get('view');
//    $logger = $c->get('logger');
//    $table = $c->get('db')->table('table_name');
//    return new TestController($view, $logger, $table);
//};
