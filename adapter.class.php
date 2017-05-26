<?php

/**
 * Interface AdapterInterface
 *
 * All adapters must implement this interface in order to be used.
 */
interface AdapterInterface {
    public function __construct(array $parameters, AdapterManager $adapterManager);
}

/**
 * Adapters are used to interface with other applications that are not native to the project.
 * Used to register and get adapters. Note that this class is a singleton.
 */
class AdapterManager {

    /**
     * @var AdapterManager instance.
     */
    private static $instance;

    /**
     * A list of all registered adapters.
     * @var array
     */
    private $adapters = array();

    /**
     * Empty private constructor to help ensure singleton status.
     */
    private function __construct() {
    }

    /**
     * Empty private clone to help ensure singleton status.
     */
    private function __clone() {
    }

    /**
     * @return AdapterManager The static adapter instance.
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new AdapterManager();
        }
        return self::$instance;
    }

    /**
     * Registers an adapter to use later in the project.
     *
     * @param string $adapterName The name of the adapter.
     * @param string $adapterPath The path to the adapter.
     * @param array $parameters   Extra parameters to pass to the adapter.
     * @return AdapterInterface The registered adapter.
     *
     * @throws Exception
     *          Adapter is already registered.
     *          Adapter is not found.
     *          Adapter does not implement AdapterInterface.
     */
    public function registerAdapter(string $adapterName, string $adapterPath = "adapters/", array $parameters = array()) {
        // Check if adapterName is in the registered adapters list, if so throw Exception
        if (array_key_exists($adapterName, $this->adapters)) {
            throw new Exception("Adapter '$adapterName' is already registered.");
        }
        // When registering a new adapter make sure the file exists
        if (file_exists($adapterPath . $adapterName . ".php")) {
            include $adapterPath . $adapterName . ".php";
        }
        //Initialize the adapter and class name
        $adapter = null;
        $className = $adapterName . "adapter";
        // Check to see adapter class exists inside of the file
        if (class_exists($className)) {
            $adapter = new $className($parameters, $this);
        } else {
            throw new Exception("Adapter '$adapterName' not found.");
        }
        // Check to see if said class implements The AdapterInterface
        if (!$adapter instanceof AdapterInterface) {
            throw new Exception('You can only register adapters that implement the AdapterInterface.');
        }
        // Add adapter and return
        $this->adapters[$adapterName] = $adapter;
        return $adapter;
    }

    /**
     * Register all internal core adapters.
     *
     * @param string $path The path to the adapters.
     * @param array $adapters List of adapters.
     */
    public function registerAdapters(string $path, array $adapters) {
        foreach ($adapters as $key => $value) {
            self::registerAdapter($key, $path, $value);
        }
    }

    /**
     * @param string $adapterName The name of the adapter.
     * @return mixed|null         The registered adapter or null if not found.
     */
    public function getAdapter(string $adapterName) {
        // Return adapter if it exists else return null
        if (array_key_exists($adapterName, $this->adapters)) {
            return $this->adapters[$adapterName];
        }
        return null;
    }

    /**
     * Creates a temporary Adapter on the fly. Helps to make more then one adapter of the same type.
     *
     * @param string $adapterName The name of the adapter.
     * @param array $parameters   Extra parameters to pass to the adapter.
     * @return mixed|null The registered adapter or null if not found.
     */
    public function createTemporaryAdapter(string $adapterName, array $parameters = array()) {
        if (array_key_exists($adapterName, $this->adapters)) {
            $className = $adapterName . "adapter";
            return new $className($parameters, $this);
        }
        return null;
    }
}