<?php

/**
 * Class Controller The web controller for templates.
 * Documented and edited by agent6262
 * @Authors probably spidEY, agent6262
 */
class Controller {

    /**
     * @var string The title of the controller.
     */
    private $controllerTitle = 'Default Page';

    /**
     * @var int The tab index of the controller. Only used if there are tabs on the page.
     */
    private $controllerTabIndex = 0;

    /**
     * @var string[] An array of all the valid keys that can be present in the parameter passed to the onPostReceived(array) method.
     */
    private $controllerPostData = array();

    /**
     * @var string The desired template style of the template.
     */
    private $templateStyle;

    /**
     * @var string The desired skin style of the template.
     */
    private $templateSkin;

    /**
     * @var Template The controllers associating template object.
     */
    private $template;

    /**
     * @var HTMLComponent[] The array of input components that are accepted by the controller.
     */
    private $components = array();

    /**
     * @var string The base url of the controller.
     */
    private $baseUrl;

    /**
     * Controller constructor.
     *
     * @param AdapterManager $adapterManager The adapter loading the controller.
     * @param string         $templateName   The name of the template file.
     * @param string         $templateStyle  The desired template style of the template.
     * @param string         $skinStyle      The desired skin style of the template.
     * @param string         $templatePath   The path to the templates.
     *
     * @throws Exception If the template file is not found.
     */
    public function __construct(AdapterManager $adapterManager, string $templateName, string $templateStyle, string $skinStyle, string $templatePath = 'templates/') {
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
     * @return string The title of the controller.
     */
    public function getControllerTitle() {
        return $this->controllerTitle;
    }

    /**
     * @param string $controllerTitle The title of the controller.
     */
    public function setControllerTitle(string $controllerTitle) {
        $this->controllerTitle = $controllerTitle;
    }

    /**
     * @return int The tab index of the controller. Only used if there are tabs on the page.
     */
    public function getControllerTabIndex() {
        return $this->controllerTabIndex;
    }

    /**
     * @param int $controllerTabIndex The tab index of the controller. Only used if there are tabs on the page.
     */
    public function setControllerTabIndex(int $controllerTabIndex) {
        $this->controllerTabIndex = $controllerTabIndex;
    }

    /**
     * @return string[] An array of all the valid keys that can be present in the parameter passed to the
     * onPostReceived(array) method.
     */
    public function getControllerPostData() {
        return $this->controllerPostData;
    }

    /**
     * @param string[] $controllerPostData An array of all the valid keys that can be present in the parameter passed
     *                                     to the onPostReceived(array) method.
     */
    public function setControllerPostData(array $controllerPostData) {
        $this->controllerPostData = $controllerPostData;
    }

    /**
     * @return string The desired template style of the template.
     */
    public function getTemplateStyle() {
        return $this->templateStyle;
    }

    /**
     * @param string $templateStyle The desired template style of the template.
     */
    public function setTemplateStyle(string $templateStyle) {
        $this->templateStyle = $templateStyle;
    }

    /**
     * @return string The desired skin style of the template.
     */
    public function getTemplateSkin() {
        return $this->templateSkin;
    }

    /**
     * @param string $templateSkin The desired skin style of the template.
     */
    public function setTemplateSkin(string $templateSkin) {
        $this->templateSkin = $templateSkin;
    }

    /**
     * @return Template The controllers associating template object.
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @param Template $template The controllers associating template object.
     */
    public function setTemplate(Template $template) {
        $this->template = $template;
    }

    /**
     * @return HTMLComponent[] The array of input components that are accepted by the controller.
     */
    public function getComponents() {
        return $this->components;
    }

    /**
     * @param HTMLComponent[] $components The array of input components that are accepted by the controller.
     */
    public function setComponents(array $components) {
        $this->components = $components;
    }

    /**
     * @return string The base url of the controller.
     */
    public function getBaseUrl() {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl The base url of the controller.
     */
    public function setBaseUrl(string $baseUrl) {
        $this->baseUrl = $baseUrl;
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