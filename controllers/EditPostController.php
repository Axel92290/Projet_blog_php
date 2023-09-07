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
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;


        $detailPost = $this->getPost($id);;

        $userId = $this->httpSession->get('user')['id'];
        $userRole = $this->httpSession->get('user')['role'];
        $postUserId = $detailPost[0]['id'];
        $this->checkRole($userRole, $userId, $postUserId);

        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            if (!$this->httpRequest->request->get('title')) {
                $this->errors[] = 'Veuillez remplir le champ titre';
            } elseif (!$this->httpRequest->request->get('content')) {
                $this->errors[] = 'Veuillez remplir le champ contenu';
            } elseif (!$this->httpRequest->request->get('chapo')) {
                $this->errors[] = 'Veuillez remplir le champ chapo';
            } else {

                $titre = ucfirst($this->cleanXSS($this->httpRequest->request->get('title')));
                $chapo = ucfirst($this->cleanXSS($this->httpRequest->request->get('chapo')));
                $contenu = ucfirst($this->cleanXSS($this->httpRequest->request->get('content')));
                $this->updatePostData($titre, $chapo, $contenu, $id);

                $this->redirect("/details-posts/$id");
                return;
            }
        }


        // On choisi la template à appeler
        $template = $this->twig->load('admin/edit.html');


        // Puis on affiche la page avec la méthode render
        
        $render = $template->render([
            'title' => 'Edition du post',
            'detailPost' => $detailPost[0],
            'errors' => $this->errors,


        ]);

        print_r($render);
    }


    private function checkRole($userRole, $userId, $postUserId)
    {
        if ($userRole === "admin" || $userId === $postUserId) {
        } else {
            $this->redirect('/error/');
            return;
        }
    }

    private function checkSession()
    {
        if (!$this->httpSession->get('user')) {
            $this->redirect('/connexion/');
            return;
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
