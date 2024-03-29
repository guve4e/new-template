<?php
require_once ("PrintHTML.php");
require_once (CONFIGURATION_PATH . "/ViewConfigurationLoader.php");
require_once (USER_SESSION_PATH . "/SessionHelper.php");


final class View
{
    /**
     * @var string
     * The path to
     * JS file for the
     * particular page.
     */
    private $viewJSPath;

    /**
     * @var string
     * The name of the page.
     * Ex: If the page file
     * is view.php,
     * $pageName is "home".
     */
    private $viewName = "";

    /**
     * @var string
     * The path to the
     * page dir.
     */
    private $viewDir = "";

    /**
     * @var string
     * The path to a
     * view.php file.
     */
    private $viewPath;

    /**
     * @var object
     * View configuration
     * loaded from json file.
     */
    private $viewConfig;

    /**
     * @var string
     * Footer is part of the view,
     * since it shares the view
     * configuration.
     */
    private $viewFooterName;

    /**
     * @var string
     * CSS class.
     */
    private $viewBodyClass;

    /**
     * @var string
     * Each view has title.
     */
    private $viewTitle;

    /**
     * @var object
     * Provides file system
     * functionality
     */
    private $file;

    /**
     * View constructor.
     *
     * @param $file FileManager object
     * @param $viewName string representing the name of the view
     * @throws Exception
     */
    public function __construct(FileManager $file, string $viewName)
    {
        if (!isset($viewName) || !isset($file))
            throw new Exception("page name is NOT set");

        // set file object
        $this->file = $file;

        // if everything ok set the page id
        $this->viewName = $viewName;

        $this->constructViewDirectoryPath();

        // set the view
        $this->constructViewPath();
        $this->constructJSPagePath();
        $this->loadViewConfig();
        $this->setBodyClass();
        $this->setViewTitle();
        $this->setViewFooter();
    }

    /**
     *
     */
    private function constructViewDirectoryPath()
    {
        $this->viewDir = VIEW_PATH . "/" . $this->viewName;
    }

    /**
     * Constructs the view path.
     * @throws Exception
     */
    private function constructViewPath()
    {
        $this->viewPath = $this->viewDir . "/view.php";
        if (!$this->file->fileExists($this->viewPath))
            throw new Exception("View does not exist!");
    }

    /**
     * Constructs the js page path.
     * JS script must be in the same file as the view
     * we will not check for set variable, because
     * some views would not have JS scripts
     */
    private function constructJSPagePath()
    {
        $this->viewJSPath = $this->viewDir . "/script.php";
    }

    /**
     * Loads information about the page
     * store it in page object
     * at this point viewConfig contains all
     * the information needed to print the page.
     * @throws Exception
     */
    private function loadViewConfig()
    {
        $viewConfig = new ViewConfigurationLoader($this->file, $this->viewName);
        $this->viewConfig = $viewConfig->getData();
    }

    /**
     * Set Body Class Style if available from
     * json config file
     * @throws Exception
     */
    private function setBodyClass()
    {
        if (!isset($this->viewConfig['body_class_style']))
            throw new Exception("Wrong page json!, 'body_class_style' property not set! ");

        // extract body_class
        $this->viewBodyClass = $this->viewConfig['body_class_style'];
    }

    /**
     * @throws Exception
     */
    private function setViewTitle()
    {
        if (!isset($this->viewConfig['title']))
            throw new Exception("Wrong page json! 'title' property not set!");

        $this->viewTitle = $this->viewConfig['title'];
    }

    private function setViewFooter()
    {
        if (isset($this->viewConfig['footer']))
            $this->viewFooterName = "/" . $this->viewConfig['footer'] . ".php";
        else
            $this->viewFooterName = null;
    }

    /**
     * @throws Exception
     */
    public function getStyles()
    {
       return $this->viewConfig['styles'];
    }

    /**
     * @throws Exception
     */
    public function getScripts()
    {
        return $this->viewConfig['scripts'];
    }

    /**
     * Includes the "*php file
     * corresponding to the view
     * and loads a script, if
     * the view has one.
     * @throws Exception
     */
    public function getPath()
    {
        return $this->viewPath;
    }

    public function getName(): string
    {
        return $this->viewName;
    }

    public function getBodyClass(): string
    {
        return $this->viewBodyClass;
    }

    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    public function getTitle(): string
    {
        return $this->viewTitle;
    }

    public function getViewJSPath(): string
    {
        return $this->viewJSPath;
    }

    public function getViewFooterName(): string
    {
        return $this->viewFooterName;
    }

    public function isFullScreen()
    {
        return $this->viewConfig['full_screen'];
    }

    public function needsAuthentication(): bool
    {
        if (isset($this->viewConfig['needs_authentication']))
            return $this->viewConfig['needs_authentication'];
        else
            return false;
    }

    public function hasFooter(): bool
    {
        return !is_null($this->viewFooterName);
    }

    public function __destruct()
    {
        unset($this->file);
        unset($this->viewPath);
        unset($this->viewBodyClass);
        unset($this->viewTitle);
        unset($this->viewName);
        unset($this->viewJSPath);
        unset($this->viewDir);
        unset($this->viewConfig);
    }
}