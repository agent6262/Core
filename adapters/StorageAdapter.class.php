<?php

/**
 * Class StorageAdapter Creates and manages different types of storage for php.
 * @author agent6262
 */
class StorageAdapter implements AdapterInterface {

    /**
     * @var string The name used in the sub storage.
     */
    private $name;

    /**
     * @var CookieStorage The cookie sub storage.
     */
    public $cookies;

    /**
     * @var SessionStorage The session sub storage.
     */
    public $session;

    /**
     * @param array          $parameters     The parameters to construct this object.
     * @param AdapterManager $adapterManager The adapter (passed to sub storage objects).
     *
     * @throws Exception If the parameters for the StorageAdapter does not contain 'name'.
     */
    public function __construct(array $parameters, AdapterManager $adapterManager) {
        // Validate array
        if (!GeneralUtility::validateArray($parameters, array('name'))) {
            throw new Exception('The parameters for the StorageAdapter does not contain \'name\'');
        }
        // Set name name for sub storage
        $this->name = $parameters['name'];
        // Init sub storage
        $this->cookies = new CookieStorage($this->name, $adapterManager);
        $this->session = new SessionStorage($this->name, $adapterManager);
    }
}

/**
 * Interface StorageInterface all types of storage's for php should implement this.
 */
interface StorageInterface {

    /**
     * @param string $identifier Name of the key for where the data will go.
     * @param mixed  $data       The data to store in the session.
     */
    public function setData(string $identifier, $data);

    /**
     * @param string $identifier The key used to get the data from the session.
     *
     * @return mixed|null If the key exists in the sub session.
     */
    public function getData(string $identifier);

    /**
     * @param string $identifier The key used to unset the data from the session.
     */
    public function destroyData(string $identifier);
}

/**
 * Class SessionStorage Creates and manages the session.
 */
class SessionStorage implements StorageInterface {

    /**
     * @var string The session index name.
     */
    private $sessionIndex;

    /**
     * @var string the name of the php session.
     */
    private $sessionName;

    /**
     * @var string the sub name of the php session.
     */
    private $name;

    /**
     * @var bool Checks to see if the sub session fields were just created.
     */
    public $justCreated = false;

    /**
     * CookieStorage constructor.
     *
     * @param string         $name           The sub name of the session.
     * @param AdapterManager $adapterManager The adapter (used to get session index).
     */
    public function __construct(string $name, AdapterManager $adapterManager) {
        // Get config adapter
        $configAdapter = $adapterManager->getAdapter('config');
        // Set local fields
        $this->sessionIndex = $configAdapter->sessionIndex;
        $this->sessionName = $configAdapter->sessionName;
        $this->name = $name;
        // Name the php session
        session_name($this->sessionName);
        // Create the php session if one does not exist
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Set session fields up if they are not already set
        if (!isset($_SESSION[$this->sessionIndex])) {
            $_SESSION[$this->sessionIndex] = array();
        }
        // Set session sub fields up if they are not already set
        if (!isset($_SESSION[$this->sessionIndex][$this->name])) {
            $_SESSION[$this->sessionIndex][$this->name] = array();
            $this->justCreated = true;
        }
    }

    /**
     * Unsets the php sub session.
     */
    public function destroyStorage() {
        if (isset($_SESSION[$this->sessionIndex][$this->name])) {
            unset($_SESSION[$this->sessionIndex][$this->name]);
        }
    }

    /**
     * @param string $identifier Name of the key for where the data will go.
     * @param mixed  $data       The data to store in the session.
     */
    public function setData(string $identifier, $data) {
        $_SESSION[$this->sessionIndex][$this->name][$identifier] = $data;
    }

    /**
     * @param string $identifier The key used to get the data from the session.
     *
     * @return mixed|null If the key exists in the sub session.
     */
    public function getData(string $identifier) {
        if (isset($_SESSION[$this->sessionIndex][$this->name][$identifier])) {
            return $_SESSION[$this->sessionIndex][$this->name][$identifier];
        }
        return null;
    }

    /**
     * @param string $identifier The key used to unset the data from the session.
     */
    public function destroyData(string $identifier) {
        unset($_SESSION[$this->sessionIndex][$this->name][$identifier]);
    }

    /**
     * @return stdClass of session fields.
     */
    public function getDataObject() {
        $data = new stdClass();
        foreach ($_SESSION[$this->sessionIndex][$this->name] as $key => $value) {
            $data->$key = $value;
        }
        return $data;
    }
}

/**
 * Class CookieStorage Creates and gets cookies.
 */
class CookieStorage implements StorageInterface {

    /**
     * @var string The secondary name of the cookie.
     */
    private $name;

    /**
     * @var string the cookie prefix.
     */
    private $prefix;

    /**
     * CookieStorage constructor.
     *
     * @param string         $name           The secondary name of the cookie.
     * @param AdapterManager $adapterManager The adapter (used to get cookie prefix)
     */
    public function __construct(string $name, AdapterManager $adapterManager) {
        $this->name = $name;
        $this->prefix = $adapterManager->getAdapter('config')->cookiePrefix;
    }

    /**
     * @param string $identifier The cookie postfix.
     * @param mixed  $data       The cookie data.
     */
    public function setData(string $identifier, $data) {
        // http://php.net/manual/en/function.setcookie.php
        setcookie("{$this->prefix}_{$this->name}_$identifier", $data, time() + (3600 * 24 * 7), null, null, null, true);
    }

    /**
     * @param string $identifier The cookie postfix.
     *
     * @return mixed|null The cookie if null if it is not set.
     */
    public function getData(string $identifier) {
        if (isset($_COOKIE["{$this->prefix}_{$this->name}_$identifier"])) {
            return $_COOKIE["{$this->prefix}_{$this->name}_$identifier"];
        }
        return null;
    }

    /**
     * @param string $identifier The cookie postfix.
     */
    public function destroyData(string $identifier) {
        if (isset($_COOKIE["{$this->prefix}_{$this->name}_$identifier"])) {
            // http://php.net/manual/en/function.setcookie.php
            setcookie("{$this->prefix}_{$this->name}_$identifier", false, 1, null, null, null, true);
        }
    }
}