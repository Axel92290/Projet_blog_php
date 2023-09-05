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


        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {

            if ($this->httpRequest->request->get('action') === "newRole") {

                $role = $this->cleanXSS($this->httpRequest->request->get('action'));
                $id = $this->cleanXSS($this->httpRequest->request->get('id'));
                $this->updateRole($role, $id);
                $this->redirect('/admin/');

            }

            if ($this->httpRequest->request->get('action') === "refuser") {
                $id = $this->cleanXSS($this->httpRequest->request->get('idComment'));
                $statut = 'refuser';
                $this->updateStatut($id, $statut);
                $this->redirect('/admin/');

            } elseif ($this->httpRequest->request->get('action') === "valider") {
                $id = $this->cleanXSS($this->httpRequest->request->get('idComment'));
                $statut = 'valider';
                $this->updateStatut($id, $statut);
                $this->redirect('/admin/');

            }
        }


        // Puis on affiche la page avec la méthode render
        $render = $template->render([
            'title' => 'Page d\'administration',
            'listComments' => $comment,
            'listUsers' => $users,
        ]);

        echo $render;

    }


    private function verifRole()
    {
        if($this->httpSession->has('user')){
            $getRoleUser = new Users();
            $role = $getRoleUser->getUsers($this->httpSession->get('user')['id']);
            if ($role[0]['role'] != "admin") {
                $this->redirect('/error/');

            }
        }else{
            $this->redirect('/error/');
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
