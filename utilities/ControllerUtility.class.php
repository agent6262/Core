<?php

/**
 * Class ControllerUtility Contains functions which are specific utility functions for controllers.
 * @author agent6262
 */
class ControllerUtility {

    /**
     * @var int Represents the call to a user defined location.
     */
    const REDIRECT_NORMAL = 1;

    /**
     * @var int Represents the call to the referrer of the page (aka the page that called the loaded page).
     */
    const REDIRECT_REFERRER = 2;

    /**
     * @var int Represents the redirect to the calling page ( $_SERVER['PHP_SELF'] ).
     */
    const REDIRECT_BASE = 3;

    /**
     * @var int Constant value that represents the _GET function.
     */
    const FORM_GET = 1;

    /**
     * @var int Constant value that represents the _POST function.
     */
    const FORM_POST = 2;

    /**
     * Attempts to load a controller with a given name.
     *
     * @param string         $controllerName The name of the controller.
     * @param AdapterManager $adapterManager The AdapterManager that will be passed to the controller.
     * @param string         $templateStyle  The name of the template style to use.
     * @param string         $skinStyle      The name of the skin style to use.
     * @param string         $controllerPath The path to the controller.
     * @param string         $templatePath   The path to the corresponding template.
     *
     * @return Controller|null The found controller or null if it was not present.
     * @throws Exception
     *              If the a controller with the given name does not exist.
     *              If The controller does not extend Controller and does not implement ControllerInterface.
     */
    public static function loadController(string $controllerName, AdapterManager $adapterManager, string $templateStyle, string $skinStyle, string $controllerPath = "controllers/", string $templatePath = "templates/") {
        // Check to see if the controller exists before including it
        if (file_exists($controllerPath . $controllerName . ".class.php")) {
            include_once $controllerPath . $controllerName . ".class.php";
        }
        // Initialize the controller and class name
        $controller = null;
        $className = $controllerName;
        // Check to see if the controller class exists inside the file
        if (class_exists($className)) {
            $templateName = str_replace('Controller', 'Template', $controllerName);
            $controller = new $className($adapterManager, $templateName, $templateStyle, $skinStyle, $templatePath);
        } else {
            throw new Exception("Controller '$controllerName' doesn't exist.");
        }
        // Check to see if the controller implements the ControllerInterface
        if (!$controller instanceof Controller) {
            throw new Exception('You can only load Controllers that extend the Controller class.');
        }
        // Return controller
        return $controller;
    }

    /**
     * @param array $data The array of properties to get.
     *
     * @return array The fetched array of properties. Can contain null items.
     */
    public static function buildControllerPost(array $data) {
        $items = array();
        foreach ($data as $item) {
            $items[$item] = self::getFormData($item, self::FORM_POST);
        }
        return $items;
    }

    /**
     * @param string $property the name of the property to fetch.
     * @param int    $method   The get or post function to use.
     *
     * @return null if the given property is not set or an invalid method was passed.
     */
    public static function getFormData(string $property, int $method) {
        if ($method == self::FORM_GET) {
            return isset($_GET[$property]) ? $_GET[$property] : null;
        } else if ($method == self::FORM_POST) {
            return isset($_POST[$property]) ? $_POST[$property] : null;
        }
        return null;
    }

    /**
     * @param string|null $url  The url to redirect to.
     * @param int         $type The type of redirect to preform.
     */
    public static function redirect($url = null, $type = self::REDIRECT_NORMAL) {
        if ($type == self::REDIRECT_REFERRER) {
            $url = self::getReferrer();
        } else if ($type == self::REDIRECT_BASE) {
            $url = $_SERVER['PHP_SELF'];
        }
        header("Location: $url");
        // https://stackoverflow.com/a/3553710/2949095
        exit;
    }

    /**
     * @return string The referrer or index.php.
     */
    public static function getReferrer() {
        return empty($_SERVER['HTTP_REFERER']) ? 'index.php' : $_SERVER['HTTP_REFERER'];
    }
}