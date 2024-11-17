<?php

namespace Config;
use App\Controllers\HomeController;


// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


//! WEB ROUTES
$routes->get('/', 'HomeController::index');
$routes->get('/contact','HomeController::contact');


//? Auth
$routes->group('auth', static function ($routes) {
    $routes->group('login', static function ($routes){
        $routes->get('', 'AuthController::login');
        $routes->post('', 'AuthController::login');
        $routes->group('google', static function ($routes){
            $routes->get('callback', 'AuthController::loginWithGoogleCallback');
        });
    });
    $routes->get('logout', 'AuthController::logout');
});

$routes->group('en-pratique', static function ($routes) {
    $routes->get('inscription', 'EnPratiqueController::inscription');
    $routes->get('cotisation', 'EnPratiqueController::cotisation');
    $routes->get('agenda', 'EnPratiqueController::agenda');
});


$routes->group('admin',['filter' => 'auth:admin,super_admin'], static function ($routes) {
    $routes->group('article',  static function ($routes) {
        $routes->get('', 'ArticleController::index');
        //Route that mach with 4 digits 
        $routes->get('([0-9]{4})', 'ArticleController::index/$1');
        $routes->get('create', 'ArticleController::create');
        $routes->post('upload','ArticleController::upload');
    });

    $routes->group('document',  static function ($routes) {
        $routes->get('', 'DocumentController::index');
        $routes->get('create', 'DocumentController::create');
        $routes->post('upload','DocumentController::upload');
        $routes->get('delete/(:any)','DocumentController::delete/$1');
    });
});

$routes->group('guide', static function ($routes) {
    $routes->get('', 'GuideController::index');
    $routes->get('document', 'GuideController::documents');
    $routes->get('document/(:num)', 'GuideController::document/$1');
});




//! API ROUTES
$routes->group('api/v1', static function ($routes) {
    $routes->setDefaultNamespace('App\Controllers\API\V1');

    //? Auth
    $routes->group('auth', static function ($routes) {
        $routes->post('login', 'AuthController::Login');
    });
});





/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
