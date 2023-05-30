<?php

namespace Controllers;

use Models\Post;

class CreatePostsController extends BaseController
{

    /**
     * @var string
     */
    private array $errors = [];



    public function createPost()
    {


        // on choisi la template à appeler
        $template = $this->twig->load('create-posts/create.html');

        $this->checkSession();


        if (!empty($_POST)) {

            if (empty($_POST['title'])) {
                $this->errors[] = 'Veuillez remplir le champ titre';
            } elseif (empty($_POST['content'])) {
                $this->errors[] = 'Veuillez remplir le champ contenu';
            }elseif (empty($_POST['chapo'])) {
                $this->errors[] = 'Veuillez remplir le champ chapo';
            } 
            else {
              
                $titre = htmlspecialchars(ucfirst(trim($_POST['title'])));
                $contenu = htmlspecialchars(ucfirst(trim($_POST['content'])));
                $chapo = htmlspecialchars(ucfirst(trim($_POST['chapo'])));
                $idUser = $_SESSION['user']['id'];
                $post = new Post();
                $post->createPost($titre, $chapo, $contenu, $idUser);
                header('Location: /listing-posts/');
                exit;
            }
        } else {
            $this->errors[] = 'Veuillez remplir tous les champs';
        }

        echo $template->render([
            'title' => 'Création d\'un post',
            'errors' => $this->errors,

        ]);
    }

    private function checkSession()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion/');
            exit;
        }
    }
}
