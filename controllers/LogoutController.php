<?php
namespace Controllers;

class LogoutController extends BaseController
{
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }
}