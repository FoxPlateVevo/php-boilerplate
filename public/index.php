<?php
require "../lib/vendor/autoload.php";

use Klein\Klein;

/*
 * Set initial configuration
 */
// Define principal path in the enviroment
define('__PATH__', __DIR__ . '/../');

// Set default time zone
date_default_timezone_set('America/Lima');

// Ignore all error reportings
error_reporting(E_ERROR);

/*
 * Start application
 */
$klein = new Klein();

// Creating validators
$klein->service()->addValidator('true', function ($variable) {
    return filter_var($variable, FILTER_VALIDATE_BOOLEAN);
});

$klein->service()->addValidator('empty', function ($variable) {
    if(is_string($variable)){
        $variable = trim($variable);
    }
    
    return empty($variable);
});

// Start session for the application
$klein->service()->startSession();

// Set default layout for all views of the application
$klein->service()->layout(__PATH__ . "/app/view/layouts/default.phtml");

// Set home site
$klein->respond("GET", "/?", function ($request, $response, $service) {
    // Header params
    $service->pageTitle = "My title Application";
    
    // Content params
    $service->title = "My simple App with PHP";
    
    // Render
    $service->render(__PATH__ . "/app/view/home/home.phtml");
});

// Set router namespaces
foreach(app_configs::getNamespaces() as $controller) {
    // Include all routes defined in a file under a given namespace
    $klein->with("/{$controller}", __PATH__ . "/app/controller/{$controller}.php");
}

// Using exact code behaviors via switch/case
$klein->onHttpError(function ($code, $router) {
    // $router is the Klein object
    switch ($code) {
        case 404:
            $router->service()->layout(__PATH__ . "/app/view/layouts/empty.phtml");
            $router->service()->render(__PATH__ . "/public/404.html");
            break;
        case 405:
            $router->response()->body(
                'You can\'t do that!'
            );
            break;
        default:
            $router->response()->body(
                'Oh no, a bad error happened that caused a '. $code
            );
    }
});

// Catch produced errors
$klein->onError(function ($klein, $errorMessage) {
    $klein->response()->json([
        "message" => $errorMessage
    ]);
});

$klein->dispatch();