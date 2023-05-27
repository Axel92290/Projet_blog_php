<?php 

namespace Controllers;
use Models\Post;
use Models\User;

class AdminController extends BaseController
{
    public function admin()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('admin/admin.html');



        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Page d\'administration',
        ]);
    }
}