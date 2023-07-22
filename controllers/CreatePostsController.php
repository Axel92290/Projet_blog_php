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




        $this->checkSession();
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;


        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest() ) {

            $this->checkFields($this->httpRequest->request->get('title'), $this->httpRequest->request->get('chapo'), $this->httpRequest->request->get('content'));
            if (empty($this->errors)) {
                $titre = ucfirst($this->cleanXSS($this->httpRequest->request->get('title')));
                $contenu = ucfirst($this->cleanXSS($this->httpRequest->request->get('content')));
                $chapo = ucfirst($this->cleanXSS($this->httpRequest->request->get('chapo')));
                $idUser = $this->httpSession->get('user')['id'];
                $this->createNewPost($titre, $chapo, $contenu, $idUser);
                header('Location: /listing-posts/');
                exit;
            } else {
                $this->errors[] = 'Veuillez remplir tous les champs';
            }
        }
        // on choisi la template à appeler
        $template = $this->twig->load('admin/create.html');
        echo $template->render([
            'title' => 'Création d\'un post',
            'errors' => $this->errors,

        ]);
    }

    private function checkSession()
    {
        if (!$this->httpSession->has('user')) {
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
