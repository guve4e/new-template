<?php
require_once ("View.php");
require_once ("Navbar.php");
require_once ("Menu.php");
require_once ("PrintHTML.php");
require_once (USER_SESSION_PATH . "/CookieSetter.php");
require_once (USER_SESSION_PATH . "/VisitorIdentifier.php");
require_once (CONFIGURATION_PATH . "/TemplateConfigurationLoader.php");
require_once (CONFIGURATION_PATH. "/SiteConfigurationLoader.php");


final class Page
{
    /**
     * @var object
     * View object.
     */
    private $view;

    /**
     * @var object
     * Navbar object.
     */
    private $navbar;

    /**
     * @var object
     * Menu object.
     */
    private $menu;

    /**
     * @var string
     * Footer
     */
    private $footer;

    /**
     * @var
     */
    private $sidebar;

    /**
     * @var object
     * Loaded info from json file.
     * Configuration for the template,
     * general css styles and js scripts.
     */
    private $templateConfiguration;

    /**
     * @var stdClass
     * Loaded info from json file.
     * Configuration for the entire site.
     */
    private $siteConfiguration;

    /**
     * @var string
     * Site title.
     */
    private $pageTitle;

    /**
     * @param $service
     * @return mixed
     */
    private function generateAuthInfo($service)
    {
        if (!array_key_exists('authorization', $service))
            return $service;

        $url = $service->authorization->url;
        $username = $service->authorization->username;
        $password = $service->authorization->password;

        $service->authorization = [
            "url" => $url,
            "token" => base64_encode("{$username}:{$password}")
        ];

        return $service;
    }

    /**
     * Page constructor.
     * Sets the name of the view
     * @param $siteConfiguration
     * @param $templateConfiguration
     */
    public function __construct($siteConfiguration, $templateConfiguration)
    {
        $this->siteConfiguration = $siteConfiguration;
        $this->templateConfiguration = $templateConfiguration;
    }

    /**
     * Extracts the title of the pge
     * from $pageConfig member variable.
     */
    public function loadPageTitle()
    {
        $this->pageTitle = $this->templateConfiguration['title'];
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function loadNavbar(FileManager $file)
    {
        $this->navbar = new Navbar($file, $this->view->getBodyClass());
    }

    public function setView(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function loadMenu(FileManager $file)
    {
        $this->menu = new Menu($file);
    }

    /**
     * @throws Exception
     */
    public function buildHead()
    {
        $viewStyles = $this->view->getStyles();
        $templateStyles = $this->templateConfiguration['styles'];
        PrintHTML::printHead($this->pageTitle, $this->view->getTitle(), $templateStyles, $viewStyles);
    }

    /**
     * Builds Closing Tags
     */
    public function buildClosingTags()
    {
        PrintHTML::printClosingTags();
    }

    /**
     * Builds Open Tags
     */
    public function buildOpenTags()
    {
        PrintHTML::printBodyOpenTag($this->view->getBodyClass());
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function buildNavbar(FileManager $file)
    {
        $path = $this->navbar->getNavbarPath();
        PrintHTML::includeHTMLPage($file, $path);
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function buildMenu(FileManager $file)
    {
        $path = $this->menu->getMenuPath();
        $conf = $this->menu->getMenuConfig();
        PrintHTML::includeHTMLPage($file, $path, $conf);
    }

    /**
     * @param FileManager $file
     * @throws Exception
     */
    public function buildView(FileManager $file)
    {
        $path = $this->view->getPath();

        // include the view first
        PrintHTML::includeHTMLPage($file, $path);
        // include the footer if any
        if ($this->view->hasFooter())
            PrintHTML::includeHTMLPage($file, FOOTER_PATH . $this->view->getViewFooterName());
    }

    /**
     * Print Site JS scripts and
     * View JS scripts.
     * Referenced from "index.php".
     * @throws Exception
     */
    public function printScripts()
    {
        PrintHTML::printListScripts($this->templateConfiguration['scripts']);
        PrintHTML::printListScripts($this->view->getScripts());
    }

    /**
     * Every view has a "script.php"
     * file congaing a javascript
     * extending the functionality
     * of the view. This method includes
     * the "script.php" file at the bottom
     * of the view.
     * Referenced from "index.php".
     * @param FileManager $file
     */
    public function loadJavaScript(FileManager $file)
    {
        $javaScriptPath = $this->view->getViewJSPath();
        // load page javascript at the bottom
        if ($file->fileExists($javaScriptPath))
            include($this->view->getViewJSPath());
    }

    public function loadCommonJavaScript(FileManager $file)
    {
        $commonScriptPath = COMMON_JS_SCRIPT . "/script.php";

        if ($file->fileExists($commonScriptPath))
            include($commonScriptPath);
    }

    /**
     * It is called from JS, to get information
     * about the primary web service API
     * @return string json or null
     */
    public function getPrimaryWebServiceInfoForJS()
    {
        if (!isset($this->siteConfiguration->web_services))
            return null;

        $service = $this->siteConfiguration->web_services;

        $service = array_map(array( __CLASS__, 'generateAuthInfo' ), $service);

        return json_encode($service);
    }

    public function __destruct()
    {
        unset($this->file);
        unset($this->viewName);
        unset($this->view);
        unset($this->menu);
        unset($this->navbar);
        unset($this->templateConfiguration);
        unset($this->pageTitle);
    }
}