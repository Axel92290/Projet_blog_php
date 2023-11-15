<?php

namespace Controllers;

use Models\Post;
use Models\Comment;


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

    } // End details().


    /**
     * Crée un commentaire pour un post spécifique.
     *
     * Cette fonction récupère le commentaire depuis la requête, le nettoie contre les attaques XSS,
     * puis le relie à l'utilisateur actuellement connecté et au post spécifié.
     *
     * @param int $id L'identifiant du post auquel ajouter le commentaire.
     * @return void
     */
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
            $createComment = new Comment();
            $createComment->createComment($comment, $idUser, $idPost);
        }

    } // End createComment().


    /**
     * Récupère les commentaires pour un post spécifique.
     *
     * Cette fonction utilise la classe Post pour récupérer les commentaires associés
     * à un post donné en fonction de son identifiant.
     *
     * @param int $id L'identifiant du post pour lequel récupérer les commentaires.
     * @return array|bool Un tableau contenant les commentaires si la récupération réussit, sinon False.
     */
    private function getComment($id)
    {
        // Récupère les commentaires pour le post avec l'ID donné.
        $getComment = new Comment();
        $getComment = $getComment->getComments($id);
        return $getComment;

    } // End getComment().


    /**
     * Vérifie les autorisations de l'utilisateur pour l'édition et la suppression d'un post.
     *
     * Cette fonction vérifie si l'utilisateur actuel a le droit d'éditer ou de supprimer un post
     * en se basant sur son rôle et l'ID du post.
     *
     * @param string $userRole    Le rôle de l'utilisateur actuel.
     * @param int    $userId      L'identifiant de l'utilisateur actuel.
     * @param int    $postUserId  L'identifiant de l'auteur du post.
     * @return array Un tableau contenant deux booléens : le premier indique si l'utilisateur peut éditer le post,
     *               le deuxième indique s'il peut le supprimer.
     */
    private function verifRole($userRole, $userId, $postUserId)
    {
        $canEditPost = false;
        $canDeletePost = false;

        // Vérifie si l'utilisateur actuel peut éditer ou supprimer le post.
        if ($userId === $postUserId || $userRole === "admin") {
            $canEditPost = true;
            $canDeletePost = true;
        }

        return [
            $canEditPost,
            $canDeletePost,
        ];

    } // End verifRole().


    /**
     * Supprime un post avec l'ID spécifié.
     *
     * Cette fonction utilise la classe "Post" pour supprimer un post de la base de données
     * en utilisant l'identifiant du post.
     *
     * @param int $id L'identifiant du post à supprimer.
     */
    private function deletePost($id)
    {
        // Supprime le post avec l'ID donné.
        $deletePost = new Post();
        $deletePost->deletePost($id);

    } // End deletePost().
} // End DetailsController().