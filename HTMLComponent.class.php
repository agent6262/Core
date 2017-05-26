<?php

/**
 * Class HTMLComponent Provides a PHP Object oriented way of constructing html elements.
 *
 * @Author agent6262
 */
class HTMLComponent {

    /**
     * @var string The html element tag.
     */
    public $tag;

    /**
     * @var array The attributes for the html element.
     */
    public $attributes;

    /**
     * @var mixed The value if any for the html element.
     */
    public $value = '';

    /**
     * @var HTMLComponent[] A list of sub html elements.
     */
    public $subHTMLComponents;

    /**
     * HTMLComponent constructor.
     *
     * @param string $tag              The html element tag.
     * @param array $attributes        The attributes for the html element.
     * @param array $subHTMLComponents A list of sub html elements.
     */
    public function __construct(string $tag, array $attributes = array(), array $subHTMLComponents = array()) {
        $this->tag = $tag;
        $this->attributes = $attributes;
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
        // Print value
        echo $this->value;
        // Print sub HTML components
        foreach ($this->subHTMLComponents as /** @var class */$subHTMLComponent) {
            $subHTMLComponent->renderHTML();
        }
        // Close Html tag
        echo '</', $this->tag, '>';
    }
}