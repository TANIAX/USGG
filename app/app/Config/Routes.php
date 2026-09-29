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
$routes->post('/contact','HomeController::sendContact');

//Newsletter (public): subscription confirmed by e-mail, unsubscription link in every e-mail
$routes->post('newsletter', 'NewsletterController::subscribe');
$routes->get('newsletter/confirmer/(:segment)', 'NewsletterController::confirm/$1');
$routes->get('newsletter/desinscription/(:segment)', 'NewsletterController::unsubscribe/$1');

//Account of the connected user
$routes->group('mon-compte', ['filter' => 'auth'], static function ($routes) {
    $routes->get('', 'AccountController::index');
    $routes->post('profil', 'AccountController::updateProfile');
    $routes->post('mot-de-passe', 'AccountController::updatePassword');
});


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
    $routes->post('inscription', 'EnPratiqueController::submitRegistration');
    $routes->get('cotisation', 'EnPratiqueController::cotisation');
    $routes->get('agenda', 'EnPratiqueController::agenda');
});


//Documents: guide admins manage the guide documents, scout admins the scout documents, the super admin both
$routes->group('admin/document', ['filter' => 'auth:super_admin,guide_admin,scout_admin'], static function ($routes) {
    $routes->get('', 'DocumentController::index');
    $routes->get('create', 'DocumentController::create');
    $routes->post('store', 'DocumentController::store');
    $routes->get('edit/(:num)', 'DocumentController::edit/$1');
    $routes->post('update/(:num)', 'DocumentController::update/$1');
    $routes->get('download/(:num)', 'DocumentController::download/$1');
    $routes->post('bulk', 'DocumentController::bulk');
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

//Accounts and roles: super admin only
//Registration requests: guide admins see the guide sections, scout admins the scout sections, the super admin all
$routes->group('admin/inscriptions', ['filter' => 'auth:super_admin,guide_admin,scout_admin'], static function ($routes) {
    $routes->get('', 'RegistrationController::index');
    $routes->post('bulk', 'RegistrationController::bulk');
    $routes->get('(:num)', 'RegistrationController::show/$1');
    $routes->post('(:num)', 'RegistrationController::update/$1');
    $routes->post('(:num)/delete', 'RegistrationController::delete/$1');
});

//Messages of the contact form
$routes->group('admin/messages', ['filter' => 'auth:super_admin,asbl_admin'], static function ($routes) {
    $routes->get('', 'ContactMessageController::index');
    $routes->post('bulk', 'ContactMessageController::bulk');
});

//Membership fees
$routes->group('admin/cotisations', ['filter' => 'auth:super_admin,asbl_admin'], static function ($routes) {
    $routes->get('', 'PricingController::edit');
    $routes->post('', 'PricingController::update');
});

//Sections, FAQ, testimonials, functions of the staff, newsletter, history: super admin
$routes->group('admin', ['filter' => 'auth:super_admin'], static function ($routes) {
    $routes->get('sections', 'SectionController::index');
    $routes->get('sections/edit/(:num)', 'SectionController::edit/$1');
    $routes->post('sections/update/(:num)', 'SectionController::update/$1');
    $routes->post('sections/move/(:num)', 'SectionController::move/$1');

    $routes->get('contenus', 'ContentController::index');
    $routes->get('contenus/(faq|temoignage)/create', 'ContentController::create/$1');
    $routes->get('contenus/(faq|temoignage)/edit/(:num)', 'ContentController::edit/$1/$2');
    $routes->post('contenus/(faq|temoignage)/save', 'ContentController::save/$1');
    $routes->post('contenus/(faq|temoignage)/save/(:num)', 'ContentController::save/$1/$2');
    $routes->post('contenus/(faq|temoignage)/(:num)/(up|down|toggle|delete)', 'ContentController::action/$1/$2/$3');

    $routes->get('fonctions', 'UserTypeController::index');
    $routes->post('fonctions/save', 'UserTypeController::save');
    $routes->post('fonctions/save/(:num)', 'UserTypeController::save/$1');
    $routes->post('fonctions/merge/(:num)', 'UserTypeController::merge/$1');
    $routes->post('fonctions/delete/(:num)', 'UserTypeController::delete/$1');

    $routes->get('historique', 'AuditController::index');
});

//Newsletter: super admin and ASBL admin
$routes->group('admin/newsletter', ['filter' => 'auth:super_admin,asbl_admin'], static function ($routes) {
    $routes->get('', 'NewsletterController::index');
    $routes->post('send', 'NewsletterController::send');
    $routes->post('delete/(:num)', 'NewsletterController::delete/$1');
});

//Dashboard: every administrator
$routes->get('admin', 'DashboardController::index', ['filter' => 'auth:admin,super_admin,guide_admin,scout_admin,asbl_admin']);

$routes->group('admin/logs', ['filter' => 'auth:super_admin'], static function ($routes) {
    $routes->get('', 'LogController::index');
    $routes->get('download/(:segment)', 'LogController::download/$1');
});

$routes->group('admin/utilisateurs', ['filter' => 'auth:super_admin'], static function ($routes) {
    $routes->get('', 'UserController::index');
    $routes->get('create', 'UserController::create');
    $routes->post('store', 'UserController::store');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->post('invite/(:num)', 'UserController::invite/$1');
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
