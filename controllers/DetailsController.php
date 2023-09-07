<?php

namespace Controllers;

use Models\Post;

class DetailsController extends BaseController
{

    
    /**
     * Affiche la page de détails d'un post.
     *
     * Cette fonction affiche la page de détails d'un post.
     * Elle permet à un utilisateur de voir les détails d'un post.
     * @param int $id L'ID du post à afficher.
     * @return void
     * 
     */
    public function details($id)
    {
        // On choisit la template à appeler.
        $template = $this->twig->load('details-posts/details.html');

        // Récupère les détails du post avec l'ID donné.
        $post = new Post();
        $detailPost = $post->getPosts($id);

        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
        
        // Vérifie si le formulaire de commentaire a été soumis.
        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            if ($this->httpRequest->request->get('submitComment') === "envoyer") {
                $this->createComment($id);
                $this->redirect("/details-posts/$id");
                return;
            }
        }

        // Récupère les commentaires pour ce post.
        $comment = $this->getComment($id);

        // Vérifie si l'utilisateur est connecté
        if ($this->httpSession->has('user')) {
            $userId = $this->httpSession->get('user')['id'];
            $userRole = $this->httpSession->get('user')['role'];
        } else {
            $userId = null;
            $userRole = null;
        }

        // Récupère l'ID de l'utilisateur qui a créé le post.
        $postUserId = $detailPost[0]['id'];

        // Vérifie si l'utilisateur actuel peut éditer ou supprimer le post.
        $canDeletePost = false;
        $canEditPost = false;
        [$canEditPost, $canDeletePost] = $this->verifRole($userRole, $userId, $postUserId);

        // Redirige vers une page d'erreur si le post n'existe pas.
        if (empty($detailPost)) {
            $this->redirect('/error/');
            return;
        }

        // Si l'action est "delete", supprime le post.
        if ($this->httpRequest->request->get('action') === "delete") {
            $this->deletePost($id);
            $this->redirect('/listing-posts/');
            return;
        }

        // Puis on affiche la page avec la méthode render.
        $render = $template->render([
            'title' => 'Détail d\'un post',
            'detailPost' => $detailPost[0],
            'listComments' => $comment,
            'canEditPost' => $canEditPost,
            'canDeletePost' => $canDeletePost,
        ]);

        print_r($render);
    }

    private function createComment($id)
    {
        // Récupère le commentaire depuis la requête en nettoyant contre les attaques XSS.
        $comment = $this->cleanXSS($this->httpRequest->request->get('comment'));
    
        // Vérifie si le commentaire n'est pas vide.
        if (!empty($comment)) {
            // Récupère l'ID de l'utilisateur courant.
            $idUser = $this->httpSession->get('user')['id'];
            $idPost = $id;
    
            // Crée un nouveau commentaire avec les informations fournies.
            $createComment = new Post();
            $createComment->createComment($comment, $idUser, $idPost);
        }
    }
    
    private function getComment($id)
    {
        // Récupère les commentaires pour le post avec l'ID donné.
        $getComment = new Post();
        $getComment = $getComment->getComments($id);
        return $getComment;
    }
    
    private function verifRole($userRole, $userId, $postUserId)
    {
        $canEditPost = false;
        $canDeletePost = false;
    
        // Vérifie si l'utilisateur actuel peut éditer ou supprimer le post.
        if ($userId == $postUserId || $userRole == "admin") {
            $canEditPost = true;
            $canDeletePost = true;
        }
    
        return [$canEditPost, $canDeletePost];
    }
    
    private function deletePost($id)
    {
        // Supprime le post avec l'ID donné.
        $deletePost = new Post();
        $deletePost->deletePost($id);
    }
    
}
