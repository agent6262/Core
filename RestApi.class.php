<?php

/**
 * All REST API functions must implement this.
 * @author  agent6262
 */
interface HandlerInterface {
    public function __construct(string $method, array $args, string $file, AdapterManager $adapterManager);

    /**
     * @return mixed API endpoint return call.
     */
    public function handle();
}

/**
 * Class RestApi Handles all RESTful API requests.
 * @author  agent6262
 */
class RestApi {

    /**
     * Extended method request type.
     */
    const HTTP_X_HTTP_METHOD = 'HTTP_X_HTTP_METHOD';

    /**
     * HTTP Method type.
     */
    const GET = 'GET';

    /**
     * HTTP Method type.
     */
    const POST = 'POST';

    /**
     * HTTP Method type.
     */
    const PUT = 'PUT';

    /**
     * HTTP Method type.
     */
    const DELETE = 'DELETE';

    /**
     * @var string The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method = '';

    /**
     * @var mixed|string The Model requested in the URI. eg: /files.
     */
    protected $endpoint = '';

    /**
     * @var array Any additional URI components after the endpoint have been removed. eg: /<endpoint>/<arg0>/<arg1>
     */
    protected $args = Array();

    /**
     * @var bool|null|string Stores the input of the PUT request.
     */
    protected $file = null;

    /**
     * Allow for CORS, assemble and pre-process the data.
     *
     * @param $request array the URI request.
     *
     * @throws Exception if there is an unexpected Header.
     */
    public function __construct(array $request) {
        // Header options
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        // Set vars
        $this->endpoint = array_shift($request);
        $this->args = $request;
        $this->method = $_SERVER['REQUEST_METHOD'];
        // Check For X HTTP request method
        if ($this->method == self::POST && array_key_exists(self::HTTP_X_HTTP_METHOD, $_SERVER)) {
            if ($_SERVER[self::HTTP_X_HTTP_METHOD] == self::DELETE) {
                $this->method = self::DELETE;
            } else if ($_SERVER[self::HTTP_X_HTTP_METHOD] == self::PUT) {
                $this->method = self::PUT;
            } else {
                throw new Exception("Unexpected Header");
            }
        }
        // Set file input for PUT request
        if ($this->method == self::PUT) {
            $this->file = file_get_contents("php://input");
        }
    }

    /**
     * @param string         $apiPath        The path to the api classes.
     * @param AdapterManager $adapterManager The adapter reference for the api endpoint.
     *
     * @return string The json encoded data.
     * @throws Exception if the endpoint file id not found.
     */
    public function processApi(string $apiPath = "api/", AdapterManager $adapterManager = null) {
        // Check to see if the api file exists before including it
        if (file_exists($apiPath . $this->endpoint . ".php")) {
            include_once $apiPath . $this->endpoint . ".php";
        } else {
            throw new Exception("Unable to find api file");
        }
        // Initialize the api object and class name
        $apiObject = null;
        $className = "api" . $this->endpoint;
        // Check to see if the api class exists inside the file
        if (class_exists($className)) {
            $apiObject = new $className($this->method, $this->args, $this->file, $adapterManager);
        } else {
            return $this->_response("No Endpoint: $this->endpoint", 404);
        }
        // Check to see if the api implements the ControllerInterface
        if (!$apiObject instanceof HandlerInterface) {
            return $this->_response("Endpoint configuration error: $this->endpoint", 500);
        }
        return $this->_response($apiObject->handle());
    }

    /**
     * @param mixed $data   The data to encode.
     * @param int   $status The status of the request.
     *
     * @return string The json encoded data.
     */
    private function _response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode($data);
    }

    /**
     * @param int $code HTTP response code.
     *
     * @return string The HTTP string response.
     */
    private function _requestStatus(int $code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return $status[$code] ? $status[$code] : $status[500];
    }
}