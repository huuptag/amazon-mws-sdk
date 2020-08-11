<?php
/**
 * File AmazonAutoloader.php
 * Created by HuuLe at 11/08/2020 11:18
 */

/************************************************************************
 * OPTIONAL ON SOME INSTALLATIONS
 *
 * Set include path to root of library
 * Only needed when running library from local directory.
 * If library is installed in PHP include path, this is not needed
 ***********************************************************************/
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__);

/************************************************************************
 * OPTIONAL ON SOME INSTALLATIONS
 *
 * Autoload function is reponsible for loading classes of the library on demand
 *
 * NOTE: Only one __autoload function is allowed by PHP per each PHP installation,
 * and this function may need to be replaced with individual require_once statements
 * in case where other framework that define an __autoload already loaded.
 *
 * However, since this library follow common naming convention for PHP classes it
 * may be possible to simply re-use an autoload mechanism defined by other frameworks
 * (provided library is installed in the PHP include path), and so classes may just
 * be loaded even when this function is removed
 ***********************************************************************/
spl_autoload_register(function ($className) {
    if (strpos($className, 'MarketplaceWebService') === false) {
        $filePath = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        require_once $filePath;
    }
    return;
});
