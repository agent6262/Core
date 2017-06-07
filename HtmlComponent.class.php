<?php

/**
 * Class HTMLComponent Provides a PHP Object-oriented way of constructing html elements.
 *
 * @Author agent6262
 */
class HtmlComponent {

    /**
     * @var string The html element tag.
     */
    private $tag;

    /**
     * @var array The attributes for the html element.
     */
    private $attributes = array();

    /**
     * @var mixed The value if any for the html element.
     */
    private $value = '';

    /**
     * @var HtmlComponent[] A list of sub html elements.
     */
    private $subHTMLComponents;

    /**
     * HTMLComponent constructor.
     *
     * @param string $tag              The html element tag.
     * @param array $attributes        The attributes for the html element.
     * @param array $subHTMLComponents A list of sub html elements.
     */
    public function __construct(string $tag, array $attributes = array(), array $subHTMLComponents = array()) {
        $this->tag = htmlspecialchars($tag);
        $this->setAttributes($attributes);
        $this->subHTMLComponents = $subHTMLComponents;
    }

    /**
     * @return string The html element tag.
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * @param string $tag The html element tag.
     */
    public function setTag(string $tag) {
        $this->tag = htmlspecialchars($tag);
    }

    /**
     * @return array The attributes for the html element.
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * @param string $key  The key of the attribute.
     * @param mixed $value The value of the attribute for the html element.
     */
    public function setAttribute(string $key, mixed $value) {
        $this->attributes[$key] = htmlspecialchars($value);
    }

    /**
     * @param array $attributes The attributes for the html element.
     */
    public function setAttributes(array $attributes) {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * @return mixed The value if any for the html element.
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param mixed $value The value if any for the html element.
     */
    public function setValue($value) {
        $this->value = htmlspecialchars($value);
    }

    /**
     * @return HtmlComponent[] A list of sub html elements.
     */
    public function getSubHTMLComponents() {
        return $this->subHTMLComponents;
    }

    /**
     * @param HtmlComponent[] $subHTMLComponents A list of sub html elements.
     */
    public function setSubHTMLComponents(array $subHTMLComponents) {
        $this->subHTMLComponents = $subHTMLComponents;
    }

    /**
     * Render the html code including all sub html components.
     */
    public function renderHTML() {
        // This is faster then string concatenation. https://www.electrictoolbox.com/php-echo-commas-vs-concatenation/
        // Init html tag
        echo '<', $this->tag, ' ';
        foreach ($this->attributes as $key => $value) {
            echo $key, '="',$value,'" ';
        }
        echo '>';

        if(empty($this->subHTMLComponents)) {
            // Print value
            echo $this->value;
        } else {
            // Print sub HTML components
            foreach ($this->subHTMLComponents as /** @var HtmlComponent */ $subHTMLComponent) {
                $subHTMLComponent->renderHTML();
            }
        }
        // Close Html tag
        echo '</', $this->tag, '>';
    }
}