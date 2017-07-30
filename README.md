![alt text](http://i.imgur.com/SLiDgDf.png "Core Logo")

# Introduction

Welcome to Core, a module based PHP library. Core is designed to be built on top of and utilized from the ground up. 
Core is not designed to be a standalone PHP application and will not function as one. Core provides modules for you to 
use when developing a PHP application, such as a `REST API` and `Web` service.

## PHP Information
> Inorder to use this library or certain functions of PHP, please enable the following in your php.ini file.

```
// OpenSSL for using HTTPS
extension=php_openssl
// CURL for reading data from URLs
extension=php_curl
```

## Core Configuration
Before you can using any of the Core modules you must load a ConfigAdapter before trying to use them or you will get an 
Exception or PHP warning/error. And when registering the ConfigAdapter the `regName` must be `config`.
> The following values must be present in your configuration file because they are utilized by core internally and will 
throw an Exception if any of them are absent. 
```php
array (
    /* ===================================================== */
    /* ==================== CORE VALUES ==================== */
    /* ===================================================== */
    /* ========== GENERAL SUBSECTION ========== */
    // Should php log the errors.
    'logErrors'             => true,
    // The default gmt time zone offset in seconds 'America/Chicago': -21600.
    'defaultTimezoneOffset' => '-21600',
    /* ========== WEB SUBSECTION ========== */
    // This will enable the web / webpage portion of core.
    'useWeb'                => true,
    // Should php display errors on the web page.
    'displayErrors'         => false,
    // The prefix for all cookies created and used by the app (including the ones in the core module).
    'cookiePrefix'          => 'core',
    // The index name for the session.
    'sessionIndex'          => 'cstorage',
    // The name of the session that will be created for the app.
    'sessionName'           => 'core_session',
    // Name of the cookie that will hold the name of the template.
    'templateCookie'        => 'template',
    // Default template style to use.
    'defaultTemplateStyle'  => 'default',
    // Name of the cookie that will hold the name of the skin.
    'skinCookie'            => 'skin',
    // Default skin style to use.
    'defaultSkinStyle'      => 'default',
    /* ========== REST API SUBSECTION ========== */
    // This will enable the api portion of core.
    'useApi'                => true,
);
```