<?php
namespace Controllers;

class LogoutController extends BaseController
{
    public function index()
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }
}