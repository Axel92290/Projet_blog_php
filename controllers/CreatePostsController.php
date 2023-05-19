<?php

namespace Controllers;

use Models\Post;

class CreatePostsController extends BaseController
{

    /**
     * @var string
     */
    private string $errors = '';
    


    public function createPost()
    {


        // on choisi la template à appeler
        $template = $this->twig->load('create-posts/create.html');


        // Puis on affiche la page avec la méthode render



        echo $template->render([
            'title' => 'Création d\'un post',
            'errors' => $this->errors

        ]);

        if (!empty($_POST['titre']) && !empty($_POST['contenu']) && !empty($_POST['submit'])) {


            $title = $_POST['titre'];
            $title = ucfirst(trim($title));
            $content = $_POST['contenu'];
            $content = ucfirst(trim($content));
            $idUser = $_SESSION['id'];

            $post = new Post();

            $post->createPost($title, $content, $idUser);
            header('Location: /');

        } else {
            $this->errors = 'Veuillez remplir tous les champs';

        }

    }
}
