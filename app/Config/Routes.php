<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/login', 'AuthController::login');
$routes->post('/loginProcess', 'AuthController::loginProcess');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'AppController::dashboard');
    $routes->get('/dashboard', 'AppController::dashboard');


    $routes->get('/user', 'AppController::user');
    $routes->post('/user/add', 'AppController::adduser');
    $routes->post('/user/edit/(:num)', 'AppController::editUser/$1');
    $routes->post('/user/delete/(:num)', 'AppController::deleteuser/$1');


    $routes->group('category', function ($routes) {
        $routes->get('/', 'Category::index');
        $routes->post('add', 'Category::addcategory');
        $routes->post('update/(:num)', 'Category::updatecategory/$1');
        $routes->post('delete/(:num)', 'Category::deletecategory/$1');
    });

    $routes->group('product', function ($routes) {
        $routes->get('/', 'ProductC::index');
        $routes->post('add', 'ProductC::addProduct');
        $routes->post('stock', 'ProductC::addStock');
        $routes->post('update/(:num)', 'ProductC::updateProduct/$1');
        $routes->post('stok/update/(:num)', 'ProductC::updateStok/$1');
        $routes->get('delete/(:num)', 'ProductC::deleteProduct/$1');
    });
    
    $routes->group('invoice', function ($routes) {
        $routes->get('/', 'InvoiceController::index');
        $routes->post('/', 'InvoiceController::index');
        $routes->post('simpan', 'InvoiceController::simpanInvoice');
        $routes->get('cancel/(:segment)', 'InvoiceController::cancelInvoice/$1');
        $routes->get('settle/(:segment)', 'InvoiceController::settleInvoice/$1');
    });
    
    $routes->group('cancelled', function ($routes) {
        $routes->get('/', 'CancelController::index');
        $routes->post('/', 'CancelController::index');
        $routes->get('restore/(:segment)', 'CancelController::restoreInvoice/$1');
    });

    $routes->group('adjustment', function ($routes) {
        $routes->get('/', 'AdjustmentController::index');
        $routes->get('cencel', 'AdjustmentController::cencelAjudtment');
        $routes->post('create', 'AdjustmentController::create');
        $routes->post('update/(:num)', 'AdjustmentController::update/$1');
        $routes->post('cancel/(:num)',  'AdjustmentController::cancel/$1');
        $routes->post('restore/(:num)', 'AdjustmentController::restore/$1');

    });



$routes->post('invoice/print-before-delete/(:segment)', 'InfoiceCetakController::d/$1');
$routes->get('cetak/print/(:segment)', 'InfoiceCetakController::index/$1');




});

$routes->get('/logout', 'AuthController::logout');
