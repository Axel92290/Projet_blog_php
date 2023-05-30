<?php

namespace Controllers;

use Models\Post;
use Models\Users;

class AdminController extends BaseController
{
    public function admin()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('admin/admin.html');


        $this->verifRole();
        $comment = $this->getComment();
        $users = $this->getUsers();

        // var_dump($comment);
        // die;
 

        if (isset($_POST['action']) && $_POST['action'] === "newRole") {
            $this->updateRole($_POST['role'], $_POST['id']);
            header('Location: /admin/');
            exit;
        }

            //0 : par défaut non publié 
            // 1: publié 
            // 2: refusé
        if(isset($_POST['action']) &&  $_POST['action'] === "refuser"){
            $id = $_POST['idComment'];
            $statut = 'refuser';
            $this->updateStatut($id, $statut);
            header('Location: /admin/');
            exit;
        }elseif(isset($_POST['action']) &&  $_POST['action'] === "valider"){
            $id = $_POST['idComment'];
            $statut = 'valider';
            $this->updateStatut($id, $statut);
            header('Location: /admin/');
            exit;
        }


        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Page d\'administration',
            'listComments' => $comment,
            'listUsers' => $users,

        ]);
    }


    private function verifRole()
    {
        $getRoleUser = new Users();
        $role = $getRoleUser->getUsers($_SESSION['user']['id']);
        if ($role[0]['role'] != "admin") {
            header('Location: /error/');
            exit;
        }
    }

    private function getComment()
    {
        $adminPage = true;
        $idPost = null;
        $getComment = new Post();
        return $getComment->getComments($idPost, $adminPage);
    }

    private function getUsers()
    {
        $getUsers = new Users();
        return $getUsers->getUsers();
    }

    private function updateRole($role, $id)
    {
        $updateRole = new Users();
        return $updateRole->updateRole($role, $id);
    }

    private function updateStatut($id, $action)
    {
        $updateStatut = new Post();
        return $updateStatut->updateStatut($id, $action);
    }
}
