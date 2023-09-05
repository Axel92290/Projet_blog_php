<?php
namespace Controllers;

class LogoutController extends BaseController
{
    public function logout()
    {
        $this->httpSession->invalidate();
        $this->httpSession->clear();
        $this->redirect('/');
        exit;
    }
}