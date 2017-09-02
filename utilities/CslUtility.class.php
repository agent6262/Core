<?php

/**
 * Class CslUtility Contains functions that are closely related to comma separated lists.
 * @author  agent6262
 */
class CslUtility {

    /**
     * Validates a comma separated list.
     *
     * @param string $str The list as a string to validate.
     *
     * @return int Returns 1 if the pattern matched 0 if it did not, and false if an error occurred.
     */
    public static function validateNumericCsl(string $str) {
        return preg_match('/^[\d]+(,[\d]+)*$|^$/', $str);
    }

    /**
     * Validates a comma separated list that contains strings.
     *
     * @param string $str The list as a string to validate.
     *
     * @return int Returns 1 if the pattern matched 0 if it did not, and false if an error occurred.
     */
    public static function validateStringCsl($str) {
        return preg_match('/^[^\s,]+(,[^\s,]+)*$|^$/', $str);
    }

    /**
     * Convert the comma separated list to an array.
     *
     * @param string $str The list as a string to validate.
     *
     * @return array|null Returns an array if the string can be successfully validated.
     */
    public static function arrayFromNumericCsl(string $str) {
        return !CslUtility::validateNumericCsl($str) ? null : (strlen($str) > 0) ? explode(',', $str) : array();
    }

    /**
     * Convert the comma separated list (which contains strings) to an array.
     *
     * @param string $str The list as a string to validate.
     *
     * @return array|null Returns an array if the string can be successfully validated.
     */
    public static function arrayFromStringCsl(string $str) {
        return !CslUtility::validateStringCsl($str) ? null : (strlen($str) > 0) ? explode(',', $str) : array();
    }
}