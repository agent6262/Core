<?php

/**
 * Class Core A static class that should be called from the project level index.php.
 *
 * @Author agent6262
 */
class Core {

    /**
     * Require all files for core to function. Also define CORE_ROOT.
     */
    public static function require() {
        // Define Core root to force the loading of controllers through the index.php
        define('CORE_ROOT', 1);
        // Load project specific constants (i.e. permissions | Log IDs | States)
        require_once 'constants.php';
        // Load the use of adapters for foreign projects
        require_once 'adapter.class.php';
    }

    /**
     * Registers all internal core adapters. Adapter must be initialized first along with the config adapter.
     *
     * @param ConfigAdapter $configAdapter   The registered configuration adapter.
     * @param AdapterManager $adapterManager The adapter register.
     *
     * @throws Exception If the config adapter is not registered.
     */
    public static function registerAdapters(ConfigAdapter $configAdapter, AdapterManager $adapterManager) {
        // Check if config is registered.
        if (!isset($configAdapter)) {
            throw new Exception("The config adapter must be registered first and must use the name 'config'");
        }
        // Set error logging before anything else
        ini_set('log_errors', $configAdapter->logErrors);
        // Register internal adapters
        $adapterManager->registerAdapters("core/adapters/", AdapterList::$adapters);
    }

    /**
     * Attempts to initialize the web module for Core.
     *
     * @param ConfigAdapter $configAdapter   The registered configuration adapter.
     * @param StorageAdapter $storageAdapter The registered storage adapter.
     * @return bool True If the useWeb is enabled in the config false otherwise.
     */
    public static function initWebModule(ConfigAdapter $configAdapter, StorageAdapter $storageAdapter) {
        // Check to make sure web module is enabled
        if (!$configAdapter->useWeb) {
            return false;
        }
        // Define the web module and error displaying
        define('CORE_WEB', 1);
        ini_set('display_errors', $configAdapter->displayErrors);
        // Require web module files
        require_once 'controller.class.php';
        require_once 'HtmlComponent.class.php';
        require_once 'template.class.php';
        // For the few pages that use date/time functions
        date_default_timezone_set($configAdapter->defaultTimezone);
        // Set default values if a new session is created
        if ($storageAdapter->session->justCreated) {
            $storageAdapter->session->setData('token', GeneralUtility::generateToken(32));
        }
        return true;
    }

    /**
     * Attempts to load and return a controller based off of the $userRequest.
     *
     * @param string $userRequest            The name of the requested controller (aka page).
     * @param ConfigAdapter $configAdapter   The registered configuration adapter.
     * @param StorageAdapter $storageAdapter The registered storage adapter.
     * @param AdapterManager $adapterManager The adapter register.
     * @return Controller|null If there is a valid controller for the users request, null otherwise.
     */
    public static function loadController(string $userRequest, ConfigAdapter $configAdapter, StorageAdapter $storageAdapter, AdapterManager $adapterManager) {
        // Load the controller with specified template and controller
        $templateStyle = $storageAdapter->cookies->getData($configAdapter->templateCookie);
        $templateStyle = isset($templateStyle) ? $templateStyle : $configAdapter->defaultTemplateStyle;
        $skinStyle = $storageAdapter->cookies->getData($configAdapter->skinCookie);
        $skinStyle = isset($skinStyle) ? $skinStyle : $configAdapter->defaultSkinStyle;
        // Load controller
        return ControllerUtility::loadController($userRequest, $adapterManager, $templateStyle, $skinStyle);
    }

    /**
     * Checks to see if there was a post sent to the controller.
     *
     * @param Controller $controller         The loaded controller for the users request.
     * @param StorageAdapter $storageAdapter The registered storage adapter.
     */
    public static function checkPost(Controller $controller, StorageAdapter $storageAdapter) {
        // Get form post token
        $postToken = ControllerUtility::getFormData('token', ControllerUtility::FORM_POST);
        // Check if the post token matches the one in the session
        if (($postToken == $storageAdapter->session->getData('token'))) {
            $post = ControllerUtility::buildControllerPost($controller->getControllerPostData());
            $controller->onPostReceived($post);
        }
    }

    /**
     * Checks to see if there was a 'userAction' sent to the controller.
     *
     * @param Controller $controller         The loaded controller for the users request.
     * @param StorageAdapter $storageAdapter The registered storage adapter.
     */
    public static function callControllerUserAction(Controller $controller, StorageAdapter $storageAdapter) {
        // Get and check the user action
        $userAction = ControllerUtility::getFormData('action', ControllerUtility::FORM_GET);
        if ($userAction != null) {
            // Get form get token
            $getToken = ControllerUtility::getFormData('token', ControllerUtility::FORM_GET);
            // Check if the get token matches the one in the session
            if (($getToken == $storageAdapter->session->getData('token'))) {
                $controller->onUserAction(strtolower($userAction));
            }
        }
    }

    /**
     * Executes the ajax function for the given controller.
     *
     * @param Controller $controller The loaded controller for the users request.
     */
    public static function callControllerAjax(Controller $controller) {
        // Execute the ajax function for the given controller
        $request = ControllerUtility::getFormData('request', ControllerUtility::FORM_GET);
        $controller->ajax($request);
    }

    /**
     * Call the controllers main function.
     *
     * @param Controller $controller The loaded controller for the users request.
     */
    public static function callControllerMain(Controller $controller) {
        $controller->main();
    }
}
?>
<?php
    session_start();
    echo $_SESSION['test']['test2']['test3'];
?>
