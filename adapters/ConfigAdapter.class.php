<?php

/**
 * Class ConfigAdapter Used to load configs fields from a flat file.
 * List of magic fields / properties that must be present.
 * @property string  defaultSkinStyle
 * @property string  defaultTemplateStyle
 * @property string  defaultTimezoneOffset
 * @property boolean displayErrors
 * @property boolean logErrors
 * @property string  skinCookie
 * @property string  templateCookie
 * @property boolean useApi
 * @property boolean useWeb
 * @author agent6262
 */
class ConfigAdapter implements AdapterInterface {

    /**
     * @var array Contains a kvp of config fields.
     */
    protected $config;

    /**
     * @var string The name of the config file.
     */
    protected $config_file;

    /**
     * @var string[] List of required magic properties that must be present in the config file.
     */
    private static $requiredProperties = array(
        'defaultSkinStyle',
        'defaultTemplateStyle',
        'defaultTimezoneOffset',
        'displayErrors',
        'logErrors',
        'skinCookie',
        'templateCookie',
        'useApi',
        'useWeb'
    );

    /**
     * @param array          $parameters     Be sure to pass the 'config_file' key.
     * @param AdapterManager $adapterManager The Adapter that loads this class.
     *
     * @throws Exception if the config file does not exist.
     */
    public function __construct(array $parameters, AdapterManager $adapterManager) {
        if (file_exists($parameters['config_file'])) {
            $this->config = include $parameters['config_file'];
            $this->config_file = $parameters['config_file'];
        } else {
            throw new Exception('The specified config file does not exist.');
        }
        // Validate all required properties
        if (!GeneralUtility::validateArray($this->config, ConfigAdapter::$requiredProperties)) {
            throw new Exception('All config properties must be present as outlined in the PHPDoc of this class.');
        }
    }

    /**
     * @param string $name Config key name.
     *
     * @return mixed|null The found value or null if key does not exist.
     */
    public function __get(string $name) {
        if (array_key_exists($name, $this->config)) {
            return $this->config[$name];
        }
        return null;
    }

    /**
     * @param string $name  The name of the key.
     * @param mixed  $value The new value for the key.
     */
    public function __set(string $name, $value) {
        $this->config[$name] = $value;
    }

    /**
     * @param string $name The name of the config key.
     *
     * @return bool If the config key is set.
     */
    public function __isset(string $name) {
        return isset($this->config[$name]);
    }

    /**
     * @return stdClass of config fields.
     */
    public function getConfigObject() {
        $data = new stdClass();
        foreach ($this->config as $key => $value) {
            $data->$key = $value;
        }
        return $data;
    }

    /**
     * @return bool|int Save the altered config values to the file.
     */
    public function commit() {
        return file_put_contents($this->config_file, "<?php" . PHP_EOL . "return " . var_export($this->config, true) . PHP_EOL . "?>");
    }
}