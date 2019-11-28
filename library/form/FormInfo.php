<?php


class FormInfo
{
    private $validVerbs = ["get", "create", "update", "add", "delete"];

    private $get;
    private $post;
    private $files;

    public function __construct(FileManager $filefileManager, array $get, array $post, array $files)
    {
        $this->get = $get;
        $this->$post = $post;
        $this->$files = $files;

        $this->validate();
    }

    private function validate()
    {

    }
}