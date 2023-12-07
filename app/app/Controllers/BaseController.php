<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * @var mixed $session The session object.
     */
    protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
    }


    /**
     * Workaround for JSON request stored in "body" field and "form-data" request content  stored in the "post" field.
     *
     * @param  IncomingRequest $request
     * @param  bool $jsonFormat
     * @return string|bool
     */
    public function getRequestInput(IncomingRequest $request, $jsonFormat = true)
    {
        $input = $request->getPost();

        if (empty($input)) {

            if ($jsonFormat) {
                $input = $this->request->getBody();
            } else {
                // Convert the request body to an associative array
                $input = json_decode($request->getBody(), true);
            }
        }
        return $input;
    }
}
