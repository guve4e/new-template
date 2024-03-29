<?php

/* define some constant paths (server dependent) */

defined("ROOT_PATH")
    or define("ROOT_PATH", realpath(dirname(__FILE__) ));

defined("DATA_RESOURCE_PATH")
    or define("DATA_RESOURCE_PATH", realpath(dirname(__FILE__) . '/data-resources'));

defined("FORM_HANDLER_PATH")
    or define("FORM_HANDLER_PATH", realpath(dirname(__FILE__) . '/library/form'));

defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("UTILITY_PATH")
    or define("UTILITY_PATH", realpath(dirname(__FILE__) . '/library/utility'));

defined("JSON_LOADER_PATH")
    or define("JSON_LOADER_PATH", realpath(dirname(__FILE__) . '/library/utility'));

defined("SITE_CONFIGURATION_PATH")
    or define("SITE_CONFIGURATION_PATH", realpath(dirname(__FILE__) . '/config.json'));

defined("CONFIGURATION_PATH")
    or define("CONFIGURATION_PATH", realpath(dirname(__FILE__) . '/library/configuration'));

defined("MENU_PATH")
    or define("MENU_PATH", realpath(dirname(__FILE__) . '/template/menu'));

defined("NAVBAR_PATH")
    or define("NAVBAR_PATH", realpath(dirname(__FILE__) . '/template/navbar'));

defined("BUILD_PATH")
    or define("BUILD_PATH", realpath(dirname(__FILE__) . '/library/page'));

defined("USER_SESSION_PATH")
    or define("USER_SESSION_PATH", realpath(dirname(__FILE__) . '/library/user-session'));

defined("HTTP_PATH")
    or define("HTTP_PATH", realpath(dirname(__FILE__) . '/library/http'));

defined("TEMPLATE_PATH")
    or define("TEMPLATE_PATH", realpath(dirname(__FILE__) . '/template'));

defined("VIEW_PATH")
    or define("VIEW_PATH", realpath(dirname(__FILE__) . '/template/views'));

defined("COMMON_JS_SCRIPT")
    or define("COMMON_JS_SCRIPT", realpath(dirname(__FILE__) . '/template/script'));

defined("LOG_PATH")
    or define("LOG_PATH", realpath(dirname(__FILE__) . '/logs'));

defined("MOCK_RESOURCES_PATH")
    or define("MOCK_RESOURCES_PATH", realpath(dirname(__FILE__) . '/mock-services'));

defined("FOOTER_PATH")
    or define("FOOTER_PATH", realpath(dirname(__FILE__) . '/template/footers'));

defined("UPLOADED_FILES_PATH")
    or define("UPLOADED_FILES_PATH", realpath(dirname(__FILE__) . '/uploaded-files'));

defined("THIRD_LIB_PATH")
    or define("THIRD_LIB_PATH", realpath(dirname(__FILE__) . '/third-party'));

/**
* Configure php.ini
* Sets the value of the given configuration option. 
* The configuration option will keep this new value 
* during the script's execution, and will be restored 
* at the script's ending.
* @param value to be set
* @param new value for the option
* @return old value on success, FALSE on failure.s
*/
if(!ini_set("error_reporting", "true")){
    die("PHP not installed!");
}
error_reporting(E_ALL);
