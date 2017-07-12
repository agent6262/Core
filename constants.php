<?php

/**
 * Denotes the version of this library.
 */
const CORE_VERSION = '0.1.0.0';

/**
 * Class AdapterList Holds a list of all adapters that are default and explicitly loaded.
 * @author Tyler Bucher <tylerb318@gmailcom>
 * @since 0.1.0.0
 */
abstract class AdapterList {

    /**
     * @var array The array of adapters that this library will load by default.
     */
    public static $adapters = array(
        'StorageAdapter' => array('name' => 'system')
    );
}