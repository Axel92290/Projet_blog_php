<?php

namespace Controllers;

use Models\Post;
use ParagonIE\AntiCSRF\AntiCSRF;

class CreatePostsController extends BaseController
{
    /**
     * Affiche et gère le formulaire de création d'un post.
     */
    public function createPost(): void
    {
        $this->checkSession();
        $csrf = new AntiCSRF();

        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            $this->checkFields(
                $this->httpRequest->request->get('title'),
                $this->httpRequest->request->get('chapo'),
                $this->httpRequest->request->get('content')
            );

            if (empty($this->errors)) {
                $titre = ucfirst($this->cleanXSS($this->httpRequest->request->get('title')));
                $chapo = ucfirst($this->cleanXSS($this->httpRequest->request->get('chapo')));
                $contenu = ucfirst($this->cleanXSS($this->httpRequest->request->get('content')));
                $idUser = (int) $this->httpSession->get('user')['id'];

                $this->createNewPost($titre, $chapo, $contenu, $idUser);
                $this->redirect('/listing-posts/');
                return;
            }

            $this->errors[] = 'Veuillez remplir tous les champs';
        }

        $template = $this->twig->load('admin/create.html');

        echo $template->render([
            'title'  => 'Création d\'un post',
            'errors' => $this->errors,
        ]);
    }

    /**
     * Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion.
     */
    private function checkSession(): void
    {
        if (!$this->httpSession->has('user')) {
            $this->redirect('/connexion/');
        }
    }

    /**
     * Enregistre un nouveau post en base de données.
     */
    private function createNewPost(string $titre, string $chapo, string $contenu, int $idUser): void
    {
        $postModel = new Post();
        $postModel->createPost($titre, $chapo, $contenu, $idUser);
    }

    /**
     * Vérifie que les champs du formulaire ne sont pas vides.
     */
    private function checkFields(?string $titre, ?string $chapo, ?string $contenu): void
    {
        if (empty($titre)) {
            $this->errors[] = 'Veuillez remplir le champ titre';
        }

        if (empty($chapo)) {
            $this->errors[] = 'Veuillez remplir le champ chapo';
        }

        if (empty($contenu)) {
            $this->errors[] = 'Veuillez remplir le champ contenu';
        }
    }
}
