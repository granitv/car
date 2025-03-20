<?php
require_once __DIR__ . '/../vendor/autoload.php';


session_start();

use App\Core\Router;
use App\Controllers\CarController;
use App\Controllers\QuoteController;
use App\Controllers\VendorController;
/**
 * * This is the main entry point for the application.
 */
$router = new Router();

$router->get('/cars', [CarController::class, 'getCarList']);
$router->post('/select-car', [CarController::class, 'selectCar']);
$router->get('/glass-options', [CarController::class, 'getGlassOptions']);
$router->post('/select-glass', [VendorController::class, 'getVendors']);
$router->post('/request-quote', [QuoteController::class, 'requestQuote']);

$router->dispatch();
