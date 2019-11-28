<?php

/**
 * The base of every page.
 */

session_start();

require_once ("../relative-paths.php");
require_once (CONFIGURATION_PATH . "/SiteConfigurationLoader.php");
require_once (FORM_HANDLER_PATH . "/FormHandler.php");
require_once (USER_SESSION_PATH . "/Chrono.php");
require_once (USER_SESSION_PATH . "/Analytics.php");
require_once (USER_SESSION_PATH . "/VisitorInfo.php");
require_once (HTTP_PATH . "/PhpHttpAdapter.php");
require_once (BUILD_PATH . "/IPageBuilder.php");
require_once (BUILD_PATH . "/PageDirector.php");
require_once (BUILD_PATH . "/PageBuilderFactory.php");
require_once (BUILD_PATH . "/IdentificationPageBuilder.php");
require_once (BUILD_PATH . "/FullScreenPageBuilder.php");


try {

    Chrono::checkTimer();

    $analytics = new Analytics(new Http(), new FileManager(), new VisitorInfo());
    $analytics->post();

    // Build the page
    $pageBuilder = PageBuilderFactory::MakePageBuilder(new FileManager(), $_GET);
    $pageDirector = new PageDirector($pageBuilder);
    $pageDirector->buildPage();

    Chrono::startTimer();

} catch (Exception $e) {
    die('Caught exception: ' . $e->getMessage() . "\n");
}

