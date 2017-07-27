<?php

/**
 * Class Controller The web controller for templates.
 * @author  spidEY
 * @author  agent6262
 * @version 1.0.0.0
 */
class Controller {

    /**
     * @var string The title of the controller.
     */
    private $controllerTitle = 'Default Page';

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
     * @var bool Should The controller render with the main template.
     */
    private $useMainTemplate;

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
        if (file_exists($templatePath . $this->templateStyle . '/' . $templateName . ".php")) {
            $this->template = new Template($templateName, $templateStyle, $skinStyle);
            $this->baseUrl = 'index.php?page=' . str_replace('Template', '', $templateName);
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
     * @return bool True if the controller should render with the main template.
     */
    public function useMainTemplate() {
        return $this->useMainTemplate;
    }

    /**
     * @param bool $useMainTemplate True if the controller should render with the main template.
     */
    public function setUseMainTemplate(bool $useMainTemplate) {
        $this->useMainTemplate = $useMainTemplate;
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
     * @param string $request The name of the ajax request that the user wants.
     */
    public function ajax(string $request) {
    }
}