<?php

require_once (BUILD_PATH . "/APageBuilder.php");


class AuthenticationPageBuilder extends APageBuilder
{
    /**
     * @var stdClass
     */
    private $siteConfig;

    /**
     * Loads information about the site
     * from config json file
     * @throws Exception
     */
    private function loadSiteConfig() : stdClass
    {
        // load configuration
        $jsonLoader = new SiteConfigurationLoader($this->file);
        return $jsonLoader->getData();
    }

    /**
     * PageBuilder constructor.
     * @param FileManager $file
     * @param View $view
     * @throws Exception
     */
    public function __construct(FileManager $file, View $view) {

        parent::__construct($file, $view);

        $this->siteConfig = $this->loadSiteConfig();
        $templateConfig = $this->loadTemplateConfig();

        $this->page = new Page($this->siteConfig, $templateConfig);
    }

    /**
     * Provides special configuration.
     * Every Builder will have its own.
     * @throws Exception
     */
    public function configure()
    {
        if (!SessionHelper::isAuthenticated())
        {
            header("Location: " . "./?page=" . "login&navigate={$this->view->getName()}");
        }

        // every page is a vew
        $this->page->setView($this->view);

        // load navbar and menu
        $this->page->loadNavbar($this->file);
        $this->page->loadMenu($this->file);

    }

    /**
     * Loads page title
     */
    public function loadPageTitle()
    {
        $this->page->loadPageTitle();
    }

    /**
     * Builds Header <head></head>
     * @throws Exception
     */
    public function buildHead()
    {
        $this->page->buildHead();
    }

    /**
     * Builds Body <body></body>
     * @throws Exception
     */
    public function buildBody()
    {
        $this->page->buildOpenTags();
        $this->page->buildNavbar($this->file);
        $this->page->buildMenu($this->file);
        $this->page->buildView($this->file);
    }

    /**
     * Print java script tags.
     * <script></script>
     * @throws Exception
     */
    public function printScripts()
    {
        $this->page->printScripts();
    }

    /**
     * Some views have custom JS
     * script, loaded at the bottom
     * of the page and a common JS
     * script that is shared among views.
     * @throws Exception
     */
    public function loadJavaScript()
    {
        // first include the common one
        $this->page->loadCommonJavaScript($this->file);
        // then the specific per view one
        $this->page->loadJavaScript($this->file);
    }

    /**
     * Prints closing tags.
     * </body>
     * </head>
     */
    public function printClosingTags()
    {
        $this->page->buildClosingTags();
    }
}