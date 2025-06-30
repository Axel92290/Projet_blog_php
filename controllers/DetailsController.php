<?php

namespace Controllers;

use Models\Post;
use Models\Comment;
use ParagonIE\AntiCSRF\AntiCSRF;

class DetailsController extends BaseController
{
    public function details(int $id): void
    {
        $template = $this->twig->load('details-posts/details.html');
        $postModel = new Post();
        $detailPost = $postModel->getPosts($id);

        $csrf = new AntiCSRF();

        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            if ($this->httpRequest->request->get('submitComment') === 'envoyer') {
                $this->createComment($id);
                $this->redirect("/details-posts/$id");
                return;
            }
        }

        $comments = $this->getComment($id);

        $userId = $this->httpSession->has('user') ? $this->httpSession->get('user')['id'] : null;
        $userRole = $this->httpSession->has('user') ? $this->httpSession->get('user')['role'] : null;

        if (empty($detailPost)) {
            $this->redirect('/error/');
            return;
        }

        $postUserId = (int) $detailPost[0]['id'];
        [$canEditPost, $canDeletePost] = $this->verifRole($userRole, $userId, $postUserId);

        if ($this->httpRequest->request->get('action') === 'delete') {
            $this->deletePost($id);
            $this->redirect('/listing-posts/');
            return;
        }

        echo $template->render([
            'title'        => 'Détail d\'un post',
            'detailPost'   => $detailPost[0],
            'listComments' => $comments,
            'canEditPost'  => $canEditPost,
            'canDeletePost'=> $canDeletePost,
        ]);
    }

    private function createComment(int $id): void
    {
        $commentText = $this->cleanXSS($this->httpRequest->request->get('comment'));

        if (!empty($commentText)) {
            $idUser = (int) $this->httpSession->get('user')['id'];
            $commentModel = new Comment();
            $commentModel->createComment($commentText, $idUser, $id);
        }
    }

    private function getComment(int $id): array
    {
        $commentModel = new Comment();
        return $commentModel->getComments($id);
    }

    private function verifRole(?string $userRole, ?int $userId, int $postUserId): array
    {
        $canEditPost = false;
        $canDeletePost = false;

        if ($userId === $postUserId || $userRole === 'admin') {
            $canEditPost = true;
            $canDeletePost = true;
        }

        return [$canEditPost, $canDeletePost];
    }

    private function deletePost(int $id): void
    {
        $postModel = new Post();
        $postModel->deletePost($id);
    }
}
