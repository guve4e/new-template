<?php

class FormHandler
{
    private $formId = "";
    private $navigateTo;
    private $entity;
    private $verb;
    private $parameter;
    private $navigateAfterUpdate;
    private $pathSuccess;
    private $pathFail;
    private $usesRecaptcha = "0";
    private $isUsingRecaptcha = false;
    private $pathSuccessParams = "";
    private $pathFailParams = "";
    private $formActionString;
    private $fileUploader;

    /**
     * @throws Exception
     */
    private function generateFormActionString()
    {
        $this->validateComponents();

        $this->formActionString = $this->navigateTo .
            "?entity={$this->entity}&verb={$this->verb}&parameter={$this->parameter}" .
            "&navigate={$this->navigateAfterUpdate}&path_success={$this->pathSuccess}" .
            "&path_fail={$this->pathFail}" . "&uses_recaptcha={$this->usesRecaptcha}";

        if ($this->pathSuccessParams != "")
            $this->formActionString .= "&path_success_params={$this->pathSuccessParams}";

        if ($this->pathFailParams != "")
            $this->formActionString .="&path_fail_params={$this->pathFailParams}";
    }

    /**
     * Called from html files.
     * If google recaptcha is used, it needs two hidden fields,
     * to hold information when passed to the php form handler.
     */
    public function generateRecaptureHiddenInputs(): string
    {
        return
            "<input type='hidden' id='g-recaptcha-response' name='g-recaptcha-response'>\n
             <input type='hidden' name='action' value='validate_captcha'>";
    }

    private function generateFormString(string $fileOption = ""): string
    {
        $formTag = "<form method='POST' id='{$this->formId}' action='{$this->getFormActionString()}' {$fileOption}>";
        $recaptchaHiddenFields  = "";
        if ($this->isUsingRecaptcha)
            $recaptchaHiddenFields = $this->generateRecaptureHiddenInputs();
        return $formTag . "\n" . $recaptchaHiddenFields;
    }

    public function __construct(FileUploader $fileUploader=null)
    {
        $this->fileUploader = $fileUploader;
    }

    /**
     * @throws Exception
     */
    public function validateComponents()
    {
        if (!isset($this->verb) || !isset($this->entity) || !isset($this->navigateAfterUpdate) ||
            !isset($this->pathFail) || !isset($this->pathSuccess) || !isset($this->navigateTo))
            throw new Exception("Not All Form Action Components are set!");
    }

    /**
     * @param mixed $entity
     * @return FormHandler
     */
    public function setEntity($entity): FormHandler
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param mixed $verb
     * @return FormHandler
     */
    public function setVerb($verb): FormHandler
    {
        $this->verb = $verb;
        return $this;
    }

    /**
     * @param mixed $parameter
     * @return FormHandler
     */
    public function setParameter($parameter): FormHandler
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * @param mixed $navigateAfterUpdate
     * @return FormHandler
     */
    public function setNavigateAfterUpdate($navigateAfterUpdate): FormHandler
    {
        $this->navigateAfterUpdate = $navigateAfterUpdate;
        return $this;
    }

    /**
     * @param mixed $pathSuccess
     * @return FormHandler
     */
    public function setPathSuccess($pathSuccess): FormHandler
    {
        $this->pathSuccess = $pathSuccess;
        return $this;
    }

    /**
     * @param mixed $pathFail
     * @return FormHandler
     */
    public function setPathFail($pathFail): FormHandler
    {
        $this->pathFail = $pathFail;
        return $this;
    }

    public function usesRecaptcha(): FormHandler
    {
        $this->isUsingRecaptcha = true;
        $this->usesRecaptcha = "1";
        return $this;
    }

    public function setPathSuccessParameters(string $params): FormHandler
    {
        $this->pathSuccessParams = $params;
        return $this;
    }

    public function setPathFailParameters(string $params): FormHandler
    {
        $this->pathFailParams = $params;
        return $this;
    }

    public function setFormId(string $formId): FormHandler
    {
        $this->formId = $formId;
        return $this;
    }

    /**
     * @param mixed $navigateTo
     * @return FormHandler
     */
    public function navigateTo($navigateTo): FormHandler
    {
        $this->navigateTo = $navigateTo . ".php";
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormActionString()
    {
        return $this->formActionString;
    }

    /**
     * @throws Exception
     */
    public function printFormAction()
    {
        $this->generateFormActionString();
        echo $this->formActionString;
    }

    /**
     * @throws Exception
     */
    public function printForm()
    {
        $this->generateFormActionString();
        echo $this->generateFormString();
    }

    /**
     * @throws Exception
     */
    public function printFileForm()
    {
        $this->generateFormActionString();
        echo $this->generateFormString("enctype='multipart/form-data'") . "\n";
    }

    /**
     * @throws Exception
     */
    public function printFileUploadForm()
    {
        $this->printFileForm();
        $this->fileUploader->printFormInputs();
    }
}
