<?php

/**
 * Class GeneralUtility Contains general utility methods for a variety of classes.
 *
 * This is a kitchen sink utility. It contains all the utilities that did not make it into their own dedicated utility
 * class. Please DO NOT add any utility methods to this class unless you can't justify making a dedicated utility class
 * for them. Also, PLEASE check the other utility classes to make sure the method(s) you're considering adding here
 * don't belong in any of them. It would be better for you to go clean your own sink (however much you may not want to)
 * than to add methods to this class. If at all possible, organize the code better and put them somewhere else.
 *
 * @Author agent6262
 */
class GeneralUtility {

    /**
     * @param int $length The length of the random string that should be returned in bytes.
     * @return string A string containing the requested number of cryptographically secure random bytes in hex.
     */
    public static function generateToken(int $length) {
        // https://stackoverflow.com/a/31683058/2949095
        return bin2hex(random_bytes($length));
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

    /**
     * @param array $array    The array of elements to validate,
     * @param array $keyCheck The array of of keys to check for.
     * @return bool false if the array does not contain a given element, true otherwise.
     */
    public static function validateArray(array $array, array $keyCheck) {
        foreach ($keyCheck as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
        }
        return true;
    }
}