<?php
require_once("./config.php");

use Core\Routing\Router;
use Core\View\View;
use Core\Model\Gallery;

/**
 * This file is used to set up the bootstrapping using routes.
 */
$router = new Router();
$router->setBasePath(str_replace("http://" . $_SERVER['SERVER_NAME'], "", BASE_DIR));

/**
 * Defined routes
 */
// Authentication
$router->addRoute("GET", "/logout", "AuthController@logout");
$router->addRoute("GET", "/login", "AuthController@showLogin");
$router->addRoute("POST", "/login", "AuthController@login");
$router->addRoute("GET", "/register", "AuthController@showRegister");
$router->addRoute("POST", "/register", "AuthController@register");

// User settings
$router->addRoute("GET", "/settings", "SettingController@showSettings");
$router->addRoute("POST", "/settings/password", "SettingController@changePassword");
$router->addRoute("GET", "/settings/delete", "SettingController@deleteUser");

// User management
$router->addRoute("GET", "/users", "UserController@index");
$router->addRoute("GET", "/user/{userId}/delete", "UserController@destroy");
$router->addRoute("GET", "/user/{userId}/promote", "UserController@promote");


// General
$router->addRoute("GET", "", "GalleryController@index");
$router->addRoute("GET", "/gallery/{galleryId}/delete", "GalleryController@delete");
$router->addRoute("GET", "/gallery/create", "GalleryController@create");
$router->addRoute("POST", "/gallery", "GalleryController@store");
$router->addRoute("GET", "/gallery/{galleryId}/edit", "GalleryController@edit");
$router->addRoute("POST", "/gallery/{galleryId}/update", "GalleryController@update");
$router->addRoute("POST", "/gallery/{galleryId}/upload", "GalleryController@upload");
$router->addRoute("GET", "/gallery/{galleryId}/share", "GalleryController@share");
$router->addRoute("GET", "/gallery/{galleryId}/unshare", "GalleryController@unShare");

$router->addRoute("GET", "/image/{imageId}", "ImageController@show");



/**
 * Dispatching and call the matched method
 */
$match = $router->dispatch();

if (DEBUG) {
    echo "<pre>";
    var_dump($match);
    echo "</pre>";
}

switch ($match["type"]) {
    case "Closure":
        $match["function"]();
        break;

    case "Controller":
        $controller = new $match["controller"]();
        if (is_null($match["parameter"])) {
            $controller->{$match["method"]}();
        } else {
            $controller->{$match["method"]}($match["parameter"]);
        }
        break;

    case "Error":
        Core\Routing\Redirect::to("/");
        break;
}

