<?php

/**
 * Class HtmlComponent Provides a PHP Object-oriented way of constructing HTML elements.
 * @Author agent6262
 */
class HtmlComponent {

    /**
     * @var string The HTML element tag.
     */
    private $tag;

    /**
     * @var array The attributes for the HTML element.
     */
    private $attributes = array();

    /**
     * @var mixed The value if any for the HTML element.
     */
    private $value = '';

    /**
     * @var HtmlComponent[] A list of sub HTML elements.
     */
    private $subHtmlComponents;

    /**
     * HTMLComponent constructor.
     *
     * @param string $tag               The HTML element tag.
     * @param array  $attributes        The attributes for the HTML element.
     * @param array  $subHtmlComponents A list of sub HTML elements.
     */
    public function __construct(string $tag, array $attributes = array(), array $subHtmlComponents = array()) {
        $this->tag = htmlspecialchars($tag);
        $this->setAttributes($attributes);
        $this->subHtmlComponents = $subHtmlComponents;
    }

    /**
     * @return string The HTML element tag.
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * @param string $tag The HTML element tag.
     */
    public function setTag(string $tag) {
        $this->tag = htmlspecialchars($tag);
    }

    /**
     * @return array The attributes for the HTML element.
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * @param string $key   The key of the attribute.
     * @param mixed  $value The value of the attribute for the HTML element.
     */
    public function setAttribute(string $key, mixed $value) {
        $this->attributes[$key] = htmlspecialchars($value);
    }

    /**
     * @param array $attributes The attributes for the HTML element.
     */
    public function setAttributes(array $attributes) {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * @return mixed The value if any for the HTML element.
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param mixed $value The value if any for the HTML element.
     */
    public function setValue($value) {
        $this->value = htmlspecialchars($value);
    }

    /**
     * @return HtmlComponent[] A list of sub HTML elements.
     */
    public function getSubHtmlComponents() {
        return $this->subHtmlComponents;
    }

    /**
     * @param HtmlComponent[] $subHtmlComponents A list of sub HTML elements.
     */
    public function setSubHtmlComponents(array $subHtmlComponents) {
        $this->subHtmlComponents = $subHtmlComponents;
    }

    /**
     * Render the HTML code including all sub HTML components.
     */
    public function renderHtml() {
        // This is faster then string concatenation. https://www.electrictoolbox.com/php-echo-commas-vs-concatenation/
        // Init HTML tag
        echo '<', $this->tag, ' ';
        foreach ($this->attributes as $key => $value) {
            echo $key, '="', $value, '" ';
        }
        echo '>';

        if (empty($this->subHtmlComponents)) {
            // Print value
            echo $this->value;
        } else {
            // Print sub HTML components
            foreach ($this->subHtmlComponents as /** @var HtmlComponent */
                     $subHtmlComponent) {
                $subHtmlComponent->renderHTML();
            }
        }
        // Close HTML tag
        echo '</', $this->tag, '>';
    }
}