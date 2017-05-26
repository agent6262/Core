<?php

/**
 * Class UtilityGeneral Contains general utility methods for a variety of classes.
 *
 * @Author agent6262
 */
class UtilityGeneral {

    /**
     * @return bool True if a database connection can be successfully established false otherwise.
     */
    public static function checkDatabaseConnection() {
        try {
            \Propel\Runtime\Propel::getConnection();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param int $length The length of the random string that should be returned in bytes.
     * @return string A string containing the requested number of cryptographically secure random bytes in hex.
     */
    public static function generateToken(int $length) {
        // https://stackoverflow.com/a/31683058/2949095
        return bin2hex(random_bytes($length));
    }

    /**
     * Validates a comma separated list.
     *
     * @param string $str The list as a string to validate.
     * @return int Returns 1 if the pattern matched 0 if it did not, and false if an error occurred.
     */
    public static function validateCSL(string $str) {
        return preg_match('/^[\d]+(,[\d]+)*$|^$/', $str);
    }

    /**
     * Validates a comma separated list that contains strings.
     *
     * @param string $str The list as a string to validate.
     * @return int Returns 1 if the pattern matched 0 if it did not, and false if an error occurred.
     */
    public static function validateStringCSL($str) {
        return preg_match('/^[^\s,]+(,[^\s,]+)*$|^$/', $str);
    }

    /**
     * Convert the comma separated list to an array.
     *
     * @param string $str The list as a string to validate.
     * @return array|null Returns an array if the string can be successfully validated.
     */
    public static function arrayFromCSL(string $str) {
        return !UtilityGeneral::validateCSL($str) ? null : (strlen($str) > 0) ? explode(',', $str) : array();
    }

    /**
     * Convert the comma separated list (which contains strings) to an array.
     *
     * @param string $str The list as a string to validate.
     * @return array|null Returns an array if the string can be successfully validated.
     */
    public static function arrayFromStringCSL(string $str) {
        return !UtilityGeneral::validateStringCSL($str) ? null : (strlen($str) > 0) ? explode(',', $str) : array();
    }

    /**
     * Custom url encoding for api arguments used in get requests.
     *
     * @param string $arg The string to be decoded.
     * @return bool|string The original data or false on failure. The returned data may be
     * binary.
     */
    public static function decodeApiStringArgument(string $arg) {
        $replacements = array('-' => '+', '_' => '/');
        return base64_decode(strtr($arg, $replacements));
    }
}