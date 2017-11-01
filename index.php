<?php

/**
 * Class Core A static class that should be called from the project level index.php.
 * @author agent6262
 */
class Core {

    /**
     * Require all files for core to function. Also define CORE_ROOT.
     */
    public static function initRequire() {
        // Define Core root to force the loading of controllers through the index.php
        define('CORE_ROOT', 1);
        // Load project specific constants (i.e. permissions | Log IDs | States)
        require_once 'constants.php';
        // Load the use of adapters for foreign projects
        require_once 'AdapterManager.class.php';
        // Load Utility classes
        require_once 'utilities/GeneralUtility.class.php';
        require_once 'utilities/CslUtility.class.php';
    }
}

/**
 * The web portion of core.
 * @author agent6262
 */
class Web {

    /**
     * Registers all internal core adapters. Adapter must be initialized first along with the config adapter.
     *
     * @param ConfigAdapter  $configAdapter  The registered configuration adapter.
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
     * @param ConfigAdapter  $configAdapter  The registered configuration adapter.
     * @param StorageAdapter $storageAdapter The registered storage adapter.
     *
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
        // Load Utility classes
        require_once 'utilities/ControllerUtility.class.php';
        // Require web module files
        require_once 'Controller.class.php';
        require_once 'HtmlComponent.class.php';
        require_once 'Template.class.php';
        // For the few pages that use date/time functions
        date_default_timezone_set(timezone_name_from_abbr('', $configAdapter->defaultTimezoneOffset, false));
        // Set default values if a new session is created
        if ($storageAdapter->session->justCreated) {
            $storageAdapter->session->setData('token', GeneralUtility::generateToken(32));
        }
        return true;
    }

    /**
     * Attempts to load and return a controller based off of the $userRequest.
     *
     * @param string         $userRequest    The name of the requested controller (aka page).
     * @param ConfigAdapter  $configAdapter  The registered configuration adapter.
     * @param StorageAdapter $storageAdapter The registered storage adapter.
     * @param AdapterManager $adapterManager The adapter register.
     *
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
     * @param Controller     $controller     The loaded controller for the users request.
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
     * @param Controller     $controller     The loaded controller for the users request.
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

/**
 * The REST API portion of core.
 * @author  agent6262
 */
class Api {

    /**
     * @param ConfigAdapter $configAdapter The registered configuration adapter.
     *
     * @return bool True if the api module is enabled false otherwise.
     */
    public static function initApiModule(ConfigAdapter $configAdapter) {
        // Check to make sure API module is enabled
        if (!$configAdapter->useApi) {
            return false;
        }
        // Define the API module and error displaying
        define('CORE_API', 1);
        // Set error logging before anything else
        ini_set('log_errors', $configAdapter->logErrors);
        // Require api module files
        require_once 'RestApi.class.php';
        // For the few pages that use date/time functions
        date_default_timezone_set(timezone_name_from_abbr('', $configAdapter->defaultTimezoneOffset, false));
        return true;
    }

    /**
     * @return array The array of URL arguments.
     */
    public static function getUrlArguments() {
        return explode('/', $_REQUEST['request']);
    }
}
