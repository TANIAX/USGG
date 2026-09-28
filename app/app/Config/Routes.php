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

    //? Forgotten password
    $routes->get('mot-de-passe-oublie', 'PasswordResetController::forgot');
    $routes->post('mot-de-passe-oublie', 'PasswordResetController::sendLink');
    $routes->get('reinitialiser/(:segment)', 'PasswordResetController::reset/$1');
    $routes->post('reinitialiser/(:segment)', 'PasswordResetController::update/$1');
});

$routes->get('actualites/(:num)', 'NewsController::show/$1');

$routes->group('en-pratique', static function ($routes) {
    $routes->get('inscription', 'EnPratiqueController::inscription');
    $routes->get('cotisation', 'EnPratiqueController::cotisation');
    $routes->get('agenda', 'EnPratiqueController::agenda');
});


$routes->group('admin',['filter' => 'auth:admin,super_admin'], static function ($routes) {
    $routes->group('document',  static function ($routes) {
        $routes->get('', 'DocumentController::index');
        $routes->get('create', 'DocumentController::create');
        $routes->post('upload','DocumentController::upload');
        $routes->get('delete/(:any)','DocumentController::delete/$1');
    });
});

//Agenda management is open to every administrator role (guide, scout, asbl and super admin)
$routes->group('admin/agenda', ['filter' => 'auth:admin,super_admin,guide_admin,scout_admin,asbl_admin'], static function ($routes) {
    $routes->get('', 'AgendaController::index');
    $routes->get('create', 'AgendaController::create');
    $routes->post('store', 'AgendaController::store');
    $routes->get('edit/(:num)', 'AgendaController::edit/$1');
    $routes->post('update/(:num)', 'AgendaController::update/$1');
    $routes->post('delete/(:num)', 'AgendaController::delete/$1');
});

//Gallery management: guide admins manage the guide albums, scout admins the scout albums, the super admin both
$routes->group('admin/galerie', ['filter' => 'auth:super_admin,guide_admin,scout_admin'], static function ($routes) {
    $routes->get('', 'AlbumController::index');
    $routes->get('create', 'AlbumController::create');
    $routes->post('store', 'AlbumController::store');
    $routes->get('album/(:num)', 'AlbumController::album/$1');
    $routes->post('album/(:num)/update', 'AlbumController::update/$1');
    $routes->post('album/(:num)/delete', 'AlbumController::delete/$1');
    $routes->post('album/(:num)/photos', 'AlbumController::photos/$1');
    $routes->post('album/(:num)/upload', 'AlbumController::upload/$1');
    $routes->post('photo/(:num)/visibility', 'AlbumController::photoVisibility/$1');
    $routes->post('photo/(:num)/delete', 'AlbumController::deletePhoto/$1');
});

//Section leaders (portraits of the home page): super admin only
$routes->group('admin/responsables', ['filter' => 'auth:super_admin'], static function ($routes) {
    $routes->get('', 'LeaderController::index');
    $routes->get('create', 'LeaderController::create');
    $routes->get('compte', 'LeaderController::account');
    $routes->post('store', 'LeaderController::store');
    $routes->get('edit/(:num)', 'LeaderController::edit/$1');
    $routes->post('update/(:num)', 'LeaderController::update/$1');
    $routes->post('delete/(:num)', 'LeaderController::delete/$1');
    $routes->post('move/(:num)', 'LeaderController::move/$1');
});

$routes->group('galerie', static function ($routes) {
    $routes->get('', 'GalleryController::index');
    $routes->get('(guide|scout)', 'GalleryController::index/$1');
    $routes->get('album/(:num)', 'GalleryController::album/$1');
    $routes->get('photo/(:num)', 'GalleryController::photo/$1');
    $routes->get('photo/(:num)/(miniature)', 'GalleryController::photo/$1/$2');
});

$routes->group('guide', static function ($routes) {
    $routes->get('', 'GuideController::index');
    $routes->get('staff', 'GuideController::staff');
    $routes->get('document', 'GuideController::documents');
    $routes->get('document/(:num)', 'GuideController::document/$1');
});

$routes->group('scout', static function ($routes) {
    $routes->get('', 'ScoutController::index');
    $routes->get('staff', 'ScoutController::staff');
    $routes->get('document', 'ScoutController::documents');
    $routes->get('document/(:num)', 'ScoutController::document/$1');
});

$routes->group('asbl', static function ($routes) {
    $routes->get('', 'AsblController::index');
    $routes->get('evenements', 'AsblController::events');
});




//! API ROUTES
$routes->group('api/v1', static function ($routes) {
    $routes->setDefaultNamespace('App\Controllers\API\V1');

    //? Auth
    $routes->group('auth', static function ($routes) {
        $routes->post('login', 'AuthController::Login');
    });

    //? News (upcoming events of the agenda)
    $routes->get('actualites', 'NewsController::index');

    //? Agenda
    $routes->group('agenda', static function ($routes) {
        $routes->get('', 'AgendaController::index');
        $routes->get('(:num)', 'AgendaController::show/$1');
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
