<?php
$app->get('/shop', \App\Controllers\ProductController::class . ':index');
$app->get('/single/{id}', \App\Controllers\ProductController::class . ':show');
$app->get('/', \App\Controllers\ProductController::class . ':home');
$app->get('/add', \App\Controllers\ProductController::class . ':add');
$app->post('/storeProduct', \App\Controllers\ProductController::class . ':storeProduct');


$app->get('/category', \App\Controllers\CategoryController::class . ':category');
$app->post('/storeCategory', \App\Controllers\CategoryController::class . ':storeCategory');
$app->get('/showCategory', \App\Controllers\CategoryController::class . ':showCategory');
$app->get('/categoryProduct/{name}', \App\Controllers\CategoryController::class . ':categoryProduct');

$app->get('/delete/{id}', \App\Controllers\CartController::class . ':delete');
$app->get('/cart', \App\Controllers\CartController::class . ':showCart');
$app->post('/delete', \App\Controllers\CartController::class . ':showCart');
$app->get('/test', \App\Controllers\CartController::class . ':test');
$app->post('/addCart/{id}', \App\Controllers\CartController::class . ':addToCart');
$app->post('/delete/{id}', \App\Controllers\CartController::class . ':delete');
$app->post('/edit/{id}/{action}/{qty}', \App\Controllers\CartController::class . ':edit');
$app->get('/TotalPrice', \App\Controllers\CartController::class . ':TotalPrice');
$app->post('/deleteCart', \App\Controllers\CartController::class . ':deleteCart');
$app->post('/storeOrder', \App\Controllers\CartController::class . ':storeOrder');

$app->get('/register', \App\Controllers\RegisterController::class . ':register');
$app->post('/storeUser', \App\Controllers\RegisterController::class . ':storeUser');

$app->get('/login', \App\Controllers\LoginController::class . ':login');
$app->post('/signIn', \App\Controllers\LoginController::class . ':signIn');
$app->get('/logout', \App\Controllers\LoginController::class . ':logout');

$app->get('/order/{orderNumber}', \App\Controllers\OrderController::class . ':showOrder');

$app->get('/account', \App\Controllers\UserController::class . ':account');

$app->get('/currencyUpdate', \App\Controllers\CurrencyController::class . ':currencyUpdate');
$app->post('/switchCurrency', \App\Controllers\CurrencyController::class . ':switchCurrency');
