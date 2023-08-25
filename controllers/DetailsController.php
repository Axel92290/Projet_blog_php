<?php

namespace Controllers;

use Models\Post;


class DetailsController extends BaseController
{
    public function details($id)
    {

        // on choisi la template à appeler
        $template = $this->twig->load('details-posts/details.html');


        $post = new Post();
        $detailPost = $post->getPosts($id);

        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;
        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest() ) {
            if ($this->httpRequest->request->get('submitComment') === "envoyer") {
                $this->createComment($id);
                header('Location: /details-posts/' . $id);
                exit;
            }
        }

        $comment = $this->getComment($id);


        if($this->httpSession->has('user')){
            $userId = $this->httpSession->get('user')['id'];
            $userRole = $this->httpSession->get('user')['role'];
        }else{
            $userId = null;
            $userRole = null;
        }


        $postUserId = $detailPost[0]['id'];


        $canDeletePost = false;
        $canEditPost = false;
        [$canEditPost, $canDeletePost] = $this->verifRole($userRole, $userId, $postUserId);



        if (empty($detailPost)) {
            header('Location: /error/');
            exit;
        }



        if ($this->httpRequest->request->get('action') === "delete") {
            $this->deletePost($id);
            header('Location: /listing-posts/');
            exit;
        }


        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Détail d\'un post',
            'detailPost' => $detailPost[0],
            'listComments' => $comment,
            'canEditPost' => $canEditPost,
            'canDeletePost' => $canDeletePost,

        ]);
    }

    private function createComment($id)
    {


        if (!empty($_POST)) {
            $comment = $this->cleanXSS($this->httpRequest->request->get('comment'));
            $idUser = $this->httpSession->get('user')['id'];
            $idPost = $id;

            $createComment = new Post();
            $createComment->createComment($comment, $idUser, $idPost);
        }
    }

    private function getComment($id)
    {

        $getComment = new Post();
        $getComment = $getComment->getComments($id);
        return $getComment;
    }

    private function verifRole($userRole, $userId, $postUserId)
    {
        $canEditPost = false;
        $canDeletePost = false;

        if ($userId == $postUserId || $userRole == "admin") {

            $canEditPost = true;
            $canDeletePost = true;
        }

        return [$canEditPost, $canDeletePost];
    }


    private function deletePost($id)
    {
        $deletePost = new Post();
        $deletePost->deletePost($id);
    }
}
