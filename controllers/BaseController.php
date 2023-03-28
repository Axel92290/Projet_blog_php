<?php

/**
 *
 */
class BaseController
{


    /**
     * @var \Twig\Loader\FilesystemLoader
     */
    protected $loader;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     *
     */
    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(APP_DIRECTORY . 'views');
        $this->twig = new \Twig\Environment($this->loader);
    }
}