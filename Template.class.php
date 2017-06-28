<?php

/**
 * Class Template Contains HTML code and some PHP for the rendered page.
 *
 * Documented and edited by agent6262
 * @Authors spidEY, agent6262
 */
class Template {

    /**
     * @var string The name of the template file.
     */
    private $fileName;

    /**
     * @var string The name of the template style.
     */
    private $templateStyle;

    /**
     * @var mixed[] Contains a list of variables to use inside of the template file.
     */
    public $args = array();

    /**
     * Template constructor.
     *
     * @param string $fileName      The name of the template file.
     * @param string $templateStyle The name of the template style.
     * @param string $skinStyle     The name of the template skin.
     */
    public function __construct(string $fileName, string $templateStyle, string $skinStyle) {
        $this->fileName = $fileName;
        $this->templateStyle = $templateStyle;
        $this->setData('skin', $skinStyle);
    }

    /**
     * @param string $name       The name of the viable.
     * @return mixed|null The variable if it exists.
     */
    public function __get(string $name) {
        if (array_key_exists($name, $this->args)) {
            return is_string($this->args[$name]) ? htmlspecialchars($this->args[$name]) : $this->args[$name];
        }
        return null;
    }

    /**
     * @param string $name The name of the variable.
     * @return bool true if the variable is set.
     */
    public function __isset(string $name) {
        return isset($this->args[$name]);
    }

    /**
     * @param string $name Name of the key.
     * @param mixed $value Value to be stored.
     */
    public function setData(string $name, mixed $value) {
        $this->args[$name] = $value;
    }

    /**
     * @param array $data Sets the entire data array.
     */
    public function setDataArray(array $data) {
        foreach ($data as $key => $value) {
            $this->args[$key] = $value;
        }
    }

    /**
     * @param string $name The name of the variable.
     * @return mixed|null If the key exists null if it does not.
     */
    public function displayRaw(string $name) {
        if (array_key_exists($name, $this->args)) {
            return $this->args[$name];
        }
        return null;
    }

    /**
     * Renders the template file.
     */
    public function render() {
        include('templates/' . strtolower($this->templateStyle) . '/' . strtolower($this->fileName) . '.php');
    }
}