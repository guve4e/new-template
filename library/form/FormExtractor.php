<?php
require_once (LIBRARY_PATH . "/form/FileUploader.php");

class FormExtractor
{
    private $fileManager;
    private $entity;
    private $verb;
    private $parameter;
    private $navigate;
    private $pathSuccess;
    private $pathFail;
    private $usesRecaptcha;
    private $data;
    private $pathSuccessParams = [];
    private $pathFailParams = [];
    private $instance;
    private $uploadedFiles;
    private $validVerbs = ["get", "create", "update", "add", "delete", "deleteAll", "authenticate"];

    private function sanitizeHtmlString(string $str)
    {
        $pattern[0] = '/\&/';
        $pattern[1] = '/</';
        $pattern[2] = "/>/";
        $pattern[3] = '/\n/';
        $pattern[4] = '/"/';
        $pattern[5] = "/'/";
        $pattern[6] = "/%/";
        $pattern[7] = '/\(/';
        $pattern[8] = '/\)/';
        $pattern[9] = '/\+/';
        $pattern[10] = '/-/';
        $replacement[0] = '&amp;';
        $replacement[1] = '&lt;';
        $replacement[2] = '&gt;';
        $replacement[3] = '<br>';
        $replacement[4] = '&quot;';
        $replacement[5] = '&#39;';
        $replacement[6] = '&#37;';
        $replacement[7] = '&#40;';
        $replacement[8] = '&#41;';
        $replacement[9] = '&#43;';
        $replacement[10] = '&#45;';
        return preg_replace($pattern, $replacement, $str);
    }

    /**
     * @param $string
     * @param string $min
     * @param string $max
     * @return bool|string|string[]|null
     * @throws Exception
     */
    private function sanitizeParanoidString($string, $min='', $max='')
    {
        $string = preg_replace("/[^a-zA-Z0-9]/", "", $string);

        $len = strlen($string);
        if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max)))
            throw new Exception("sanitizeParanoidString failed!");

        return $string;
    }

    /**
     * @param string $verb
     * @throws Exception
     */
    private function validateVerb(string $verb)
    {
        if (!in_array($verb, $this->validVerbs))
            throw new Exception("Not a valid verb!");
    }

    /**
     * @param $path
     * @param $objectForValidation
     * @throws Exception
     */
    private function isValidPath($path, $objectForValidation)
    {
        $validPaths = [];
        foreach(glob($path .'/*') as $file)
        {
            $parts = explode("/", $file);
            array_push($validPaths, str_replace(".php", "", end($parts)));
        }

        if (!in_array($objectForValidation, $validPaths))
            throw new Exception("Not a valid Form Extractor object!");
    }

    /**
     * @param $parameter
     * @return bool|string|string[]|null
     * @throws Exception
     */
    private function sanitizeParameter($parameter)
    {
        return $this->sanitizeParanoidString($parameter, 1, 30);
    }

    /**
     * @param array $post
     * @return array
     * @throws Exception
     */
    private function sanitizePost(array $post)
    {
        $sanitizedPost = [];
        foreach($post as $key => $value)
        {
            $key = $this->sanitizeParanoidString($key);
            $value = htmlentities(trim($value), ENT_NOQUOTES);
            $sanitizedPost[$key] = $value;
        }
        return $sanitizedPost;
    }

    /*
     * @$navigate $objectForNavigation
     * @throws Exception
     */
    private function isValidBoolean($navigate)
    {
        if (!($navigate == "0" || $navigate == "1"))
            throw new Exception("Not a valid navigation object!");
    }

    /**
     * Validates if the super-globals $_GET and $_POST
     * contain the right information.
     *
     * @param $get: representing the $_GET super-global
     * @param $post: representing the $_POST super-global
     * @throws Exception
     */
    private function validateAttributes(FileManager $file, array $get, array $post)
    {
        if(/*!isset($file) ||*/ !isset($get['entity']) || !isset($get["verb"]) || !isset($get['parameter']) ||
            !isset($get['navigate']) || !isset($get['path_success']) || !isset($get['path_fail'])
            || !isset($get['uses_recaptcha']) /*|| !isset($post)*/)
            throw new Exception("Invalid Arguments in FormExtractor.");


        $this->isValidPath(DATA_RESOURCE_PATH, $get['entity']);
        $get['parameter'] = $this->sanitizeParameter($get['parameter']);
        $this->validateVerb($get['verb']);
        $this->isValidPath(VIEW_PATH, $get['path_success']);
        $this->isValidPath(VIEW_PATH, $get['path_fail']);
        $this->isValidBoolean($get['navigate']);
        $this->isValidBoolean($get['uses_recaptcha']);

        if(!is_array($post))
            throw new Exception("Fields attribute is not of type array.");
    }

    /**
     * @param array $parameters
     * @return array
     * @throws Exception
     */
    private function retrieveParameters(array $parameters): array
    {
        $parametersArray = [];
        foreach($parameters as $parameter)
        {
            $params = explode(":", $parameter);

            if (count($params) != 2)
                throw new Exception("Bad path parameters!");

            $parametersArray[$params[0]] = $params[1];
        }

        return $parametersArray;
    }

    /**
     * @param string $parameters
     * @return string
     * @throws Exception
     */
    private function constructPath(string $parameters): string
    {
        $parametersArray = $this->retrieveParameters(explode("/", $parameters));

        $paramSting = "";

        foreach($parametersArray as $key => $value)
            $paramSting .= "&{$key}={$value}";

        return $paramSting;
    }

    /**
     * @throws Exception
     */
    private function constructPathFail()
    {
        $this->pathFail .= $this->constructPath($this->pathFailParams);
    }

    /**
     * @throws Exception
     */
    private function constructPathSuccess()
    {
        $this->pathSuccess .= $this->constructPath($this->pathSuccessParams);
    }

    /**
     * @param $success
     * @throws Exception
     */
    private function navigate($success)
    {
        if ($success === null)
            throw new Exception("Resource method '{$this->verb}' didn't return success boolean!");

        if($this->navigate)
        {
            // if response is not successful
            if ($success) {
                header("Location: " . "./?page=" . $this->pathSuccess);
            } else { // if response is successful
                // Log it first
                // TODO
                header("Location: " . "./?page=" . $this->pathFail);
            }
        }
    }

    /**
     * @param $post
     * @return mixed
     * @throws Exception
     */
    private function uploadFiles($post)
    {
        if (isset($this->uploadedFiles['files']))
        {
            $fileUploader = new FileUploader($this->fileManager);
            $fileUploader->setInfo($post);
            $fileUploader->setUploadedFiles($this->uploadedFiles['files']);
            $fileUploader->uploadFiles();
        }
        return $post;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function callResource()
    {
        // if file exists include it
        $class = ucfirst($this->entity);
        $path = DATA_RESOURCE_PATH . "/" . $class . ".php";

        if ($this->fileManager->fileExists($path))
            require_once($path);
        else
            throw new Exception("Resource file {$path} does not exists!");

        // text substitution
        // @example:
        // $test = new Test();
        $this->instance = new $class();

        // get the request method
        $method = $this->verb;

        // invoke method with the right parameter, if provided
        return $this->instance->$method($this->parameter, $this->data);
    }

    private function determineNavigation(array $get)
    {
        if ( $get['navigate'] == "true" || $get['navigate'] == "1")
            $this->navigate = true;
        else
            $this->navigate = false;
    }

    /**
     * ServiceForm constructor.
     *
     * @param array $get : representing $_GET
     * @param array $post : representing $_POST
     * @throws Exception
     */
    public function __construct(FileManager $filefileManager, FormSettings $settings,
                                array $get, array $post, array $files)
    {
        $this->validateAttributes($filefileManager, $get, $post);
        $this->fileManager = $filefileManager;
        $this->uploadedFiles = $files;

        // Now we know that _GET and _POST are proper
        $this->determineNavigation($get);

        $this->entity = $get['entity'];
        $this->verb = $get["verb"];
        $this->parameter = $get['parameter'];
        $this->pathSuccess = $get['path_success'];
        $this->pathFail = $get['path_fail'];


        // check if navigation with parameters

        if (isset($get['path_success_params'])) {
            $this->pathSuccessParams = $get['path_success_params'];
            $this->constructPathSuccess();
        }

        if (isset($get['path_fail_params'])) {
            $this->pathFailParams = $get['path_fail_params'];
            $this->constructPathFail();
        }

        $settings->validate();
        $settings->do();

        $post = $this->sanitizePost($post);
        $post = $this->uploadFiles($post);

        if (!empty($post))
            $this->data = json_decode(json_encode($post));
        else
            $this->data = new StdClass();

        $success = $this->callResource();
        $this->navigate($success);
    }
}