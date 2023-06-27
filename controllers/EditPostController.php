<?php

namespace Controllers;

use Models\Post;

class EditPostController extends BaseController
{
    /**
     * @var string
     */
    private array $errors = [];


    public function editPost($id)
    {

        $this->checkSession();


        $detailPost = $this->getPost($id);;

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];
        $postUserId = $detailPost[0]['id'];
        $this->checkRole($userRole, $userId, $postUserId);

        if ($this->httpRequest->isMethod('POST')) {
            if (!empty($_POST)) {
                if (empty($_POST['title'])) {
                    $this->errors[] = 'Veuillez remplir le champ titre';
                } elseif (empty($_POST['content'])) {
                    $this->errors[] = 'Veuillez remplir le champ contenu';
                } elseif (empty($_POST['chapo'])) {
                    $this->errors[] = 'Veuillez remplir le champ chapo';
                } else {

                    $titre = $this->cleanXSS($this->httpRequest->request->get('title'));
                    $titre = htmlspecialchars(ucfirst(trim($titre)));
                    $chapo = $this->cleanXSS($this->httpRequest->request->get('chapo'));
                    $chapo = htmlspecialchars(ucfirst(trim($chapo)));
                    $contenu = $this->cleanXSS($this->httpRequest->request->get('content'));
                    $contenu = htmlspecialchars(ucfirst(trim($contenu)));
                    $this->updatePostData($titre, $chapo, $contenu, $id);

                    header("Location: /details-posts/$id");
                    exit;
                }
            }
        }


        // on choisi la template à appeler
        $template = $this->twig->load('admin/edit.html');


        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Edition du post',
            'detailPost' => $detailPost[0],
            'errors' => $this->errors,


        ]);
    }


    private function checkRole($userRole, $userId, $postUserId)
    {
        if ($userRole === "admin" || $userId === $postUserId) {
        } else {
            header('Location: /error/');
            exit;
        }
    }

    private function checkSession()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion/');
            exit;
        }
    }



    private function getPost($id)
    {
        $post = new Post();
        $detailPost = $post->getPosts($id);
        return $detailPost;
    }


    private function updatePostData($titre, $chapo, $contenu, $id)
    {
        $post = new Post();
        $post->updatePost($titre, $chapo, $contenu, $id);
    }
}
