<?php
mb_internal_encoding("UTF-8");

header("Access-Control-Allow-Origin: *"); // docasne!!
header('Access-Control-Allow-Headers: Content-Type');
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
header("X-Frame-Options: SAMEORIGIN");
header("Connection: close");
// header("Strict-Transport-Security: max-age=31536000"); - v produkcii

define("APP_ROOT", __DIR__);

require_once(APP_ROOT . "/app/core/Config.php");
require_once(APP_ROOT . "/app/core/Database.php");
require_once(APP_ROOT . "/app/core/DbHandler.php");
require_once(APP_ROOT . "/app/core/HttpRequestsHandler.php");
require_once(APP_ROOT . "/app/utils/ClientEcho.php");
require_once(APP_ROOT . "/app/utils/Validation.php");
require_once(APP_ROOT . "/app/utils/PassHash.php");
require_once(APP_ROOT . "/app/utils/MonthNames_en.php");
require_once(APP_ROOT . "/config/statusCodes.php");
require_once(APP_ROOT . "/config/responseTypes.php");
require_once(APP_ROOT . "/config/constants.php");
require(APP_ROOT . "/libs/Slim/Slim.php");

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim ();

$app->get('/', function () use ($app) {
    // test
    Database::getInstance(); // ok?
    echo('idze');
});

/* ----------------------EVENTS METHODS------------------------- */

/**
 * Listing all events
 * url - /events
 * method - GET
 */
$app->post('/events', function () use ($app) {
    $validation = new Validation ();
    $validation->verifyRequiredParams(array(
        'page',
        'results'
    ));

    // reading post params
    $page = $app->request->post('page');
    $results = $app->request->post('results');

    $dbHandler = new DbHandler ();

    // fetching all events
    $result = $dbHandler->getAllEvents($page, $results);

    ClientEcho::buildResponse($result, EVENT);
});

/**
 * Listing all venue events
 * url - /events
 * method - GET
 */
$app->post('/events/venue', function () use ($app) {
    $validation = new Validation ();
    $validation->verifyRequiredParams(array(
        'idVenue',
        'page',
        'results'
    ));
    // reading post params
    $idVenue = $app->request->post('idVenue');
    $page = $app->request->post('page');
    $results = $app->request->post('results');

    $dbHandler = new DbHandler ();

    // fetching all events
    $result = $dbHandler->getAllVenueEvents($idVenue, $page, $results);

    ClientEcho::buildResponse($result, EVENT);
});


/**
 * Listing all most viewed events
 * url - /events
 * method - GET
 */
$app->post('/events/mostviewed', function () use ($app) {
    $validation = new Validation ();
    $validation->verifyRequiredParams(array(
        'page',
        'results'
    ));

    // reading post params
    $page = $app->request->post('page');
    $results = $app->request->post('results');

    $dbHandler = new DbHandler ();

    // fetching all events
    $result = $dbHandler->getAllEvents($page, $results);

    ClientEcho::buildResponse($result, EVENT);
});

/**
 * Listing all city events
 * url - /events/city
 * method - POST
 */
$app->post('/events/city', function () use ($app) {
    $validation = new Validation ();
    $validation->verifyRequiredParams(array(
        'city',
        'page',
        'results'
    ));

    // reading post params
    $city = $app->request->post('city');
    $page = $app->request->post('page');
    $results = $app->request->post('results');

    $dbHandler = new DbHandler ();

    // fetching all events
    $result = $dbHandler->getCityEvents($city, $page, $results);

    ClientEcho::buildResponse($result, EVENT);
});

/**
 * Listing all cities
 * url - /events
 * method - GET
 */
$app->get('/cities', function () {
    $dbHandler = new DbHandler ();

    // fetching all events
    $result = $dbHandler->getCities();

    ClientEcho::buildResponse($result, CITIES);
});

/**
 * Listing single event
 * url - /events/:id
 * method - GET
 */
$app->get('/events/:id', function ($idEvent) {
    $dbHandler = new DbHandler ();

    // fetching single events
    $result = $dbHandler->getSingleEvent($idEvent);
    ClientEcho::buildResponse($result, EVENT);
});

/*--------------VENUES METHODS---------------*/

/**
 * Listing all venues
 * url - /venues
 * method - POST
 */
$app->post('/venues', function () use ($app) {
    $validation = new Validation ();
    $validation->verifyRequiredParams(array(
        'page',
        'results'
    ));

    // reading post params
    $page = $app->request->post('page');
    $results = $app->request->post('results');

    $dbHandler = new DbHandler ();

    // fetching all venues
    $result = $dbHandler->getAllVenues($page, $results);

    ClientEcho::buildResponse($result, VENUE);
});

/**
 * Listing single venue
 * url - /venues/:id
 * method - GET
 */
$app->get('/venues/:id', function ($idVenue) {
    $dbHandler = new DbHandler ();

    // fetching single venue
    $result = $dbHandler->getSingleVenue($idVenue);
    ClientEcho::buildResponse($result, VENUE);
});

/**
 * Search venues by name
 * url - /venues/name
 * method - POST
 */
$app->post('/venues/name', function () use ($app) {
    $validation = new Validation ();
    $validation->verifyRequiredParams(array(
        'name',
        'page',
        'results'
    ));

    // reading post params
    $name = $app->request->post('name');
    $page = $app->request->post('page');
    $results = $app->request->post('results');

    $dbHandler = new DbHandler ();

    // fetching all events
    $result = $dbHandler->getSearchedVenues($name, $page, $results);

    ClientEcho::buildResponse($result, VENUE);
});

/*--------------Cards Methods----------*/

/**
 * Get Cards
 * url - /cards
 * method - GET
 */

$app->get('/cards', function () use ($app) {
    $dbHandler = new DbHandler();

    $result = $dbHandler->getAllCards();

    ClientEcho::buildResponse($result, CARDS);


});

$app->run();