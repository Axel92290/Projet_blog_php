<?php

class CreatePostsController extends BaseController
{
    public function createPost()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('create-posts/create.html');


        // Puis on affiche la page avec la méthode render
        echo $template->render(['title' => 'Création d\'un post']);


        $post = new Post();

        if (isset($_REQUEST['creer'])) {

            $title = $_REQUEST['title'];
            $content = $_REQUEST['content'];
            $dateCreation = date('Y-m-d H:i:s');
            $dateModification = date('Y-m-d H:i:s');
            $id_user = 1;
            $post->insertPost($title, $content, $dateCreation, $dateModification, $id_user);
        }
    }
}