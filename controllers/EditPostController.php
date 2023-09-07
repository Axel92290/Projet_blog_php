<?php

namespace Controllers;

use Models\Post;

class EditPostController extends BaseController
{
    public function editPost($id)
    {
        // Vérifie la session de l'utilisateur
        $this->checkSession();
        
        // Vérifie le jeton CSRF
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;

        // Récupère les détails du post
        $detailPost = $this->getPost($id);
        $userId = $this->httpSession->get('user')['id'];
        $userRole = $this->httpSession->get('user')['role'];
        $postUserId = $detailPost[0]['id'];

        // Vérifie le rôle de l'utilisateur
        $this->checkRole($userRole, $userId, $postUserId);

        if (!$this->httpRequest->isMethod('POST') || !$csrf->validateRequest()) {
            // Gère la méthode HTTP invalide ou le jeton CSRF incorrect ici
            return;
        }

        if (!$this->httpRequest->request->get('title')) {
            $this->errors[] = 'Veuillez remplir le champ titre';
        } elseif (!$this->httpRequest->request->get('content')) {
            $this->errors[] = 'Veuillez remplir le champ contenu';
        } elseif (!$this->httpRequest->request->get('chapo')) {
            $this->errors[] = 'Veuillez remplir le champ chapo';
        } else {
            // Nettoie et récupère les données du formulaire
            $titre = ucfirst($this->cleanXSS($this->httpRequest->request->get('title')));
            $chapo = ucfirst($this->cleanXSS($this->httpRequest->request->get('chapo')));
            $contenu = ucfirst($this->cleanXSS($this->httpRequest->request->get('content')));

            // Met à jour les données du post
            $this->updatePostData($titre, $chapo, $contenu, $id);
            
            // Redirige vers la page des détails du post
            $this->redirect("/details-posts/$id");
            return;
        }

        // Choisi la template à appeler
        $template = $this->twig->load('admin/edit.html');

        // Affiche la page avec la méthode render
        $render = $template->render([
            'title' => 'Edition du post',
            'detailPost' => $detailPost[0],
            'errors' => $this->errors,
        ]);

        print_r($render);
    }

    private function checkRole($userRole, $userId, $postUserId)
    {
        // Vérifie si l'utilisateur a le rôle "admin" ou est l'auteur du post
        if ($userRole === "admin" || $userId === $postUserId) {
            // L'utilisateur a les permissions nécessaires
        } else {
            // Redirige vers une page d'erreur
            $this->redirect('/error/');
            return;
        }
    }

    private function checkSession()
    {
        // Vérifie si la session de l'utilisateur est active
        if (!$this->httpSession->get('user')) {
            // Redirige vers la page de connexion
            $this->redirect('/connexion/');
            return;
        }
    }

    private function getPost($id)
    {
        // Récupère les détails du post avec l'ID donné
        $post = new Post();
        $detailPost = $post->getPosts($id);
        return $detailPost;
    }

    private function updatePostData($titre, $chapo, $contenu, $id)
    {
        // Met à jour les données du post
        $post = new Post();
        $post->updatePost($titre, $chapo, $contenu, $id);
    }
}
