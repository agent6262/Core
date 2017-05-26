<?php

/**
 * Interface ControllerInterface
 *
 * @Authors probably spidEY, agent6262
 */
interface ControllerInterface {
    public function __construct(AdapterManager $adapterManager, $templateStyle, $skinStyle);
}

/**
 * Class Controller The web controller for templates.
 *
 * Documented and edited by agent6262
 * @Authors probably spidEY, agent6262
 */
class Controller {

    /**
     * @var string The title of the controller.
     */
    public $controllerTitle = 'Default Page';

    /**
     * @var int The tab index of the controller. Only used if there are tabs on the page.
     */
    public $controllerTabIndex  = 0;

    /**
     * @var mixed[] An array of data posted to the controller from the template.
     */
    public $controllerPostData = array();

    /**
     * @var string The desired template style of the template.
     */
    public $templateStyle;

    /**
     * @var string The desired skin style of the template.
     */
    public $templateSkin;

    /**
     * @var Template The controllers associating template object.
     */
    public $template;

    /**
     * @var HTMLComponent[] The array of input components that are accepted by the controller.
     */
    public $components = array();

    /**
     * @var string The base url of the controller.
     */
    public $baseUrl;

    /**
     * Controller constructor.
     *
     * @param AdapterManager $adapterManager The adapter loading the controller.
     * @param string $templateName           The name of the template file.
     * @param string $templatePath           The path to the templates.
     * @param string $templateStyle          The desired template style of the template.
     * @param string $skinStyle              The desired skin style of the template.
     *
     * @throws Exception If the template file is not found.
     */
    public function __construct(AdapterManager $adapterManager, string $templateName, string $templatePath = 'templates/', string $templateStyle, string $skinStyle) {
        // Set desired template and skin info
        $this->templateStyle = $templateStyle;
        $this->templateSkin = $skinStyle;
        // Check to see if the template exists before creating it
        if (file_exists($templatePath . $templateName . ".php")) {
            $this->template = new Template($templateName, $templateStyle, $skinStyle);
            $this->baseUrl = 'index.php?page=' . strtolower($templateName);
        } else {
            throw new Exception("Template of '$templateName' not found.");
        }
    }

    /**
     * The primary function for the controller.
     */
    public function main() {
    }

    /**
     * @param array $data The data array of post variables.
     */
    public function onPostReceived(array $data) {
    }

    /**
     * @param string $action The name of the action the user preformed.
     */
    public function onUserAction(string $action) {
    }

    /**
     * @param mixed $request The name of the ajax request that the user wants.
     */
    public function ajax(mixed $request) {
    }
}