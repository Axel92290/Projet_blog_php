<?php

namespace Controllers;

use Models\Comment;
use Models\Post;
use Models\Users;



class AdminController extends BaseController
{


    /**
     * Affiche la page d'administration.
     *
     * Cette fonction affiche la page d'administration du site, permettant à un administrateur
     * de gérer les commentaires et les utilisateurs.
     */
    public function admin()
    {
        // On choisi la template à appeler.
        $template = $this->twig->load('admin/admin.html');

        // Vérifie le rôle de l'utilisateur connecté.
        $this->verifRole();

        // Récupère la liste des commentaires.
        $comment = $this->getComment();

        // Récupère la liste des utilisateurs.
        $users = $this->getUsers();

        // Crée une instance du système de protection CSRF.
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;

        // Vérifie si une requête POST a été soumise et que le jeton CSRF est valide.
        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            // Gestion des actions en fonction de la demande.

            // Si l'action est de changer le rôle d'un utilisateur.
            if ($this->httpRequest->request->get('action') === "newRole") {
                $role = $this->cleanXSS($this->httpRequest->request->get('role'));
                $id = $this->cleanXSS($this->httpRequest->request->get('id'));
                $this->updateRole($role, $id);
                $this->redirect('/admin/');
            }

            // Si l'action est de refuser un commentaire.
            if ($this->httpRequest->request->get('action') === "refuser") {
                $id = $this->cleanXSS($this->httpRequest->request->get('idComment'));
                $statut = 'refuser';
                $this->updateStatut($id, $statut);
                $this->redirect('/admin/');
            }

            // Si l'action est de valider un commentaire.
            elseif ($this->httpRequest->request->get('action') === "valider") {
                $id = $this->cleanXSS($this->httpRequest->request->get('idComment'));
                $statut = 'valider';
                $this->updateStatut($id, $statut);
                $this->redirect('/admin/');
            }
        }

        // Puis on affiche la page avec la méthode render.
        $render = $template->render([
            'title' => 'Page d\'administration',
            'listComments' => $comment,
            'listUsers' => $users,
        ]);

        print_r($render);

    } // End admin().


    /**
     * Vérifie le rôle de l'utilisateur et redirige en cas de non-administrateur.
     *
     * Cette fonction vérifie le rôle de l'utilisateur connecté en utilisant la session.
     * Si l'utilisateur n'a pas le rôle "admin", il est redirigé vers la page d'erreur.
     */
    private function verifRole()
    {
        if ($this->httpSession->has('user')) {
            $getRoleUser = new Users();
            $role = $getRoleUser->getUsers($this->httpSession->get('user')['id']);
            if ($role[0]['role'] !== "admin") {
                $this->redirect('/error/');
            }
        } else {
            $this->redirect('/error/');
        }

    } // End verifRole().


    /**
     * Récupère les commentaires pour la page d'administration.
     *
     * Cette fonction récupère les commentaires à afficher sur la page d'administration.
     *
     * @return array Un tableau contenant les commentaires.
     */
    private function getComment()
    {
        $adminPage = true;
        $idPost = null;
        $getComment = new Comment();
        return $getComment->getComments($idPost, $adminPage);

    } // End getComment().


    /**
     * Récupère la liste des utilisateurs.
     *
     * Cette fonction récupère la liste complète des utilisateurs depuis la base de données.
     *
     * @return array Un tableau contenant les données des utilisateurs.
     */
    private function getUsers()
    {
        $getUsers = new Users();
        return $getUsers->getUsers();

    } // End getUsers().


    /**
     * Cette fonction met à jour le rôle d'un utilisateur spécifié dans la base de données.
     *
     * @param string $role   Le nouveau rôle de l'utilisateur.
     * @param int    $id     L'identifiant de l'utilisateur à mettre à jour.
     * @return bool          True si la mise à jour a réussi, sinon False.
     */

    private function updateRole($role, $id)
    {
        $updateRole = new Users();
        return $updateRole->updateRole($role, $id);

    } // End updateRole().


    /**
     * Met à jour le statut d'un commentaire.
     *
     * Cette fonction met à jour le statut (valider ou refuser) d'un commentaire dans la base de données.
     *
     * @param int    $id      L'identifiant du commentaire à mettre à jour.
     * @param string $action  L'action à effectuer (valider ou refuser).
     * @return bool           True si la mise à jour a réussi, sinon False.
     */
    private function updateStatut($id, $action)
    {
        $updateStatut = new Comment();
        return $updateStatut->updateStatut($id, $action);

    } // End updateStatut().
} // End AdminController().