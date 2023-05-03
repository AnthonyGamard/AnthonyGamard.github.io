<?php

namespace App;

use App\Controllers\DefaultController;
use CPE\Framework\AbstractApplication;
use CPE\Framework\Router;
use App\Controllers\GameController;
use App\Controllers\UserController;
use App\Controllers\NavireController;
use App\Controllers\AuthController;

use RuntimeException;

class Application extends AbstractApplication
{
    public function run()
    {
        // default response if no route found
        $requestParams = [];

        // map all routes to corresponding controllers/actions
        $this->router = new Router($this);
        $this->router->mapDefault(DefaultController::class, '404');



        // GAME Routes
        $this->router->map('/', DefaultController::class, 'index', 'GET');
        $this->router->map('/game', GameController::class, 'play', 'POST');
        $this->router->map('/games/:idGame/ships', GameController::class, 'placeShip', 'PUT');
        $this->router->map('/games/:idGame/board', GameController::class, 'getBoard', 'GET');
        $this->router->map('/games/:idGame', GameController::class, 'deleteGame', 'DELETE');
        $this->router->map('/game/:idGame/details', GameController::class, 'getGameDetails', 'GET');

        // $this->router->map('/games/:idGame', GameController::class, 'GetGameById', 'GET');
        // $this->router->map('/games', GameController::class, 'CreateGame', 'POST');
        // $this->router->map('/games/:idGame/join', GameController::class, 'JoinGame', 'PUT');
        // $this->router->map('/games/:idGame/play', GameController::class, 'Play', 'POST');

        // USER Routes
        $this->router->map('/users/signin', UserController::class, 'ConnexionUser', 'GET');
        $this->router->map('/users/signup', UserController::class, 'CreateUser', 'GET');


        // NAVIRE Routes
        $this->router->map('/ships', NavireController::class, 'GetAllShips', 'GET');


        // verify the validity of the jwt token
        $this->router->map('/verifyJWT', AuthController::class, 'verifyJWT', 'GET');



        $route = $this->router->findRoute();

        if (empty($route)) {
            throw new RuntimeException('no available route found for this URL');
        }
        $controller = $this->router->getController($route->controller);
        $controller->execute($route->action, $route->requestParams);
    }
}
