<?php


namespace App\Controllers;


use App\Components\SessionFactory;
use App\Components\SessionHelpers;
use App\Models\Category;
use App\Models\User;
use Psr\Container\ContainerInterface;

class UserController
{
    private $c;

    private $user;

    public function __construct(ContainerInterface $container, User $user)
    {
        $this->c = $container;
        $this->user = $user;
    }

    public function account($request, $response)
    {


        $id = SessionHelpers::user()->id; //add fnc
        $user = User::with(['orders' => function ($query) {
            $query->with(['products' => function ($query) {
                $query->withPivot('qty');
            }]);
        }
        ])->where('id', $id)->first();

        return $this->c->view->render($response, 'account.html.twig', [
            'orders' => $user->orders,
            'user' => $user,
        ]);
    }
}