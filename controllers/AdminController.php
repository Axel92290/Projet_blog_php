<?php

namespace Controllers;

use Models\Comment;
use Models\Users;
use ParagonIE\AntiCSRF\AntiCSRF;

class AdminController extends BaseController
{
    /**
     * Affiche la page d'administration.
     */
    public function admin(): void
    {
        $this->verifRole();

        $template = $this->twig->load('admin/admin.html');
        $comments = $this->getComment();
        $users = $this->getUsers();
        $csrf = new AntiCSRF();

        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            $action = $this->httpRequest->request->get('action');

            if ($action === 'newRole') {
                $role = $this->cleanXSS($this->httpRequest->request->get('role'));
                $id = (int) $this->cleanXSS($this->httpRequest->request->get('id'));
                $this->updateRole($role, $id);
                $this->redirect('/admin/');
            }

            if ($action === 'refuser') {
                $id = (int) $this->cleanXSS($this->httpRequest->request->get('idComment'));
                $this->updateStatut($id, 'refuser');
                $this->redirect('/admin/');
            }

            if ($action === 'valider') {
                $id = (int) $this->cleanXSS($this->httpRequest->request->get('idComment'));
                $this->updateStatut($id, 'valider');
                $this->redirect('/admin/');
            }
        }

        echo $template->render([
            'title' => 'Page d\'administration',
            'listComments' => $comments,
            'listUsers' => $users,
        ]);
    }

    /**
     * Vérifie le rôle de l'utilisateur et redirige s'il n'est pas admin.
     */
    private function verifRole(): void
    {
        if ($this->httpSession->has('user')) {
            $userModel = new Users();
            $role = $userModel->getUsers((int) $this->httpSession->get('user')['id']);

            if ($role[0]['role'] !== 'admin') {
                $this->redirect('/error/');
            }
        } else {
            $this->redirect('/error/');
        }
    }

    /**
     * Récupère les commentaires pour la page d'administration.
     */
    private function getComment(): array
    {
        $commentModel = new Comment();
        return $commentModel->getComments(null, true);
    }

    /**
     * Récupère la liste des utilisateurs.
     */
    private function getUsers(): array
    {
        $userModel = new Users();
        return $userModel->getUsers();
    }

    /**
     * Met à jour le rôle d'un utilisateur.
     */
    private function updateRole(string $role, int $id): void
    {
        $userModel = new Users();
        $userModel->updateRole($role, $id);
    }

    /**
     * Met à jour le statut d'un commentaire.
     */
    private function updateStatut(int $id, string $action): void
    {
        $commentModel = new Comment();
        $commentModel->updateStatut($id, $action);
    }
}
