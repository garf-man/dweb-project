<?php


namespace App\Controllers;


use App\Components\sessionHelpers;
use App\Models\User;
use Exception;
use Psr\Container\ContainerInterface;
use Slim\Http\Response;
use App\Models\Product;
use http\Env\Request;


class LoginController extends Controller
{

    private $c;

    private $user;

    public function __construct(ContainerInterface $container, User $user)
    {
        $this->c = $container;
        $this->user = $user;
    }

    public function login($request, $response)
    {
        if (!empty($_SESSION['user'])) {
            return $response->withRedirect('/home');
        } else {
            return $this->c->view->render($response, 'signIn.html.twig');
        }
    }

    /**
     * @param $request \Slim\Http\Request
     * @param $response Response
     * @return mixed
     * @throws Exception
     */
    public function signIn($request, $response)
    {
        $data = $request->getParsedBody();
        $errors = validateEmptyData($data, ['email', 'password']);
        if ($errors) {
            return $this->c->view->render($response, 'signIn.html.twig', [
                'emptyErrors' => $errors
            ]);
        }
        $email = $data['email'];
        $enterPassword = $data['password'];
        $user = $this->user->select('id','name','email','password')->where('email', $email)->first();
        $user = $user->toArray();

        if ($user && password_verify($enterPassword, $user['password'])) {
            unset($user['password']);
            SessionHelpers::addByKey('user',$user);
            return $response->withRedirect('/home');
        } else {
            return $this->c->view->render($response, 'signIn.html.twig', [
                'error' => ['wrongPasswordOrLogin' => 'Неверный пароль или логин'],
                'old' => $data,
            ]);
        }
    }

    /**
     * @param $request \Slim\Http\Request
     * @param $response Response
     * @return mixed
     * @throws Exception
     */
    public function logout($request, $response)
    {
        $sessionHelpers = new sessionHelpers();
        $sessionHelpers->destroyByKey('user');
        return $response->withRedirect('/');
    }

}