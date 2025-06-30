<?php

namespace Controllers;

use Models\Post;
use ParagonIE\AntiCSRF\AntiCSRF;

class EditPostController extends BaseController
{
    public function editPost(int $id): void
    {
        $this->checkSession();

        $csrf = new AntiCSRF();
        $postModel = new Post();
        $detailPost = $this->getPost($id);

        $userId = $this->httpSession->get('user')['id'];
        $userRole = $this->httpSession->get('user')['role'];
        $postUserId = (int) $detailPost[0]['id'];

        $this->checkRole($userRole, $userId, $postUserId);

        // Si la requête est POST, on traite l'édition
        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            $title = $this->httpRequest->request->get('title');
            $chapo = $this->httpRequest->request->get('chapo');
            $content = $this->httpRequest->request->get('content');

            if (!$title || !$chapo || !$content) {
                $this->errors[] = 'Veuillez remplir tous les champs';
            } else {
                $titre = ucfirst($this->cleanXSS($title));
                $chapo = ucfirst($this->cleanXSS($chapo));
                $contenu = ucfirst($this->cleanXSS($content));

                $this->updatePostData($titre, $chapo, $contenu, $id);
                $this->redirect("/details-posts/$id");
                return;
            }
        }

        $template = $this->twig->load('admin/edit.html');
        echo $template->render([
            'title'      => 'Edition du post',
            'detailPost' => $detailPost[0],
            'errors'     => $this->errors,
        ]);
    }

    private function checkRole(string $userRole, int $userId, int $postUserId): void
    {
        if ($userRole !== 'admin' && $userId !== $postUserId) {
            $this->redirect('/error/');
        }
    }

    private function checkSession(): void
    {
        if (!$this->httpSession->has('user')) {
            $this->redirect('/connexion/');
        }
    }

    private function getPost(int $id): array
    {
        $postModel = new Post();
        return $postModel->getPosts($id);
    }

    private function updatePostData(string $titre, string $chapo, string $contenu, int $id): void
    {
        $postModel = new Post();
        $postModel->updatePost($titre, $chapo, $contenu, $id);
    }
}
