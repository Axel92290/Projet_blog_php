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
        $template = $this->twig->load('admin/create.html');

        $this->checkSession();

        if ($this->httpRequest->isMethod('POST')) {
            if (!empty($_POST)) {
                $this->checkFields($_POST['title'], $_POST['chapo'], $_POST['content']);

                if (empty($this->errors)) {
                    $titre = $this->cleanXSS($this->httpRequest->request->get('title'));
                    $titre = htmlspecialchars(ucfirst(trim($titre)));
                    $contenu = $this->cleanXSS($this->httpRequest->request->get('content'));
                    $contenu = htmlspecialchars(ucfirst(trim($contenu)));
                    $chapo = $this->cleanXSS($this->httpRequest->request->get('chapo'));
                    $chapo = htmlspecialchars(ucfirst(trim($chapo)));
                    $idUser = $_SESSION['user']['id'];
                    $this->createNewPost($titre, $chapo, $contenu, $idUser);
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
    }

    private function checkSession()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion/');
            exit;
        }
    }

    private function createNewPost($titre, $chapo, $contenu, $idUser)
    {
        $post = new Post();
        $post->createPost($titre, $chapo, $contenu, $idUser);
    }

    private function checkFields($titre, $chapo, $contenu)
    {
        if (empty($titre)) {
            $this->errors[] = 'Veuillez remplir le champ titre';
        } elseif (empty($chapo)) {
            $this->errors[] = 'Veuillez remplir le champ chapo';
        } elseif (empty($contenu)) {
            $this->errors[] = 'Veuillez remplir le champ contenu';
        }
    }
}
