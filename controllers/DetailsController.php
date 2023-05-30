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

        if(isset($_POST['submitComment']) &&  $_POST['submitComment'] === "envoyer"){
            $this->createComment($id);
            header('Location: /details-posts/'.$id);
            exit;
        }
        
        $comment = $this->getComment($id);

    
        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];
        $postUserId = $detailPost[0]['id'];


        $canDeletePost = false;
        $canEditPost = false;
        [$canEditPost, $canDeletePost] = $this->verifRole($userRole, $userId, $postUserId);



        if(empty($detailPost)){
            header('Location: /error/');
            exit;
        }



        if(isset($_POST['action']) &&  $_POST['action'] === "delete"){
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

            //0 : par défaut non publié 
            // 1: publié 
            // 2: refusé

            if (!empty($_POST)) {
                $comment = htmlspecialchars($_POST['comment']);
                $idUser = $_SESSION['user']['id'];
                $idPost = $id;

                $createComment = new Post();
                $createComment->createComment($comment, $idUser, $idPost);
            }


           
    }

    private function getComment($id){

        $getComment = new Post();
        $getComment = $getComment->getComments($id);
        return $getComment;

        

    }

    private function verifRole($userRole, $userId, $postUserId)
    {

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



