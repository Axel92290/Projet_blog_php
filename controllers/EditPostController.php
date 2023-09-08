<?php

namespace Controllers;

use Models\Post;

class EditPostController extends BaseController
{


    /**
     * Affiche la page d'édition d'un post.
     *
     * Cette fonction affiche la page d'édition d'un post.
     * Elle permet à un utilisateur de modifier un post.
     * @param int $id L'ID du post à modifier.
     * @return void
     * 
     */
    public function editPost($id)
    {
        // Vérifie la session de l'utilisateur.
        $this->checkSession();

        // Vérifie le jeton CSRF.
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;

        // Récupère les détails du post.
        $detailPost = $this->getPost($id);
        $userId = $this->httpSession->get('user')['id'];
        $userRole = $this->httpSession->get('user')['role'];
        $postUserId = $detailPost[0]['id'];

        // Vérifie le rôle de l'utilisateur.
        $this->checkRole($userRole, $userId, $postUserId);

        if (!$this->httpRequest->isMethod('POST') || !$csrf->validateRequest()) {
            // Gère la méthode HTTP invalide ou le jeton CSRF incorrect ici.
            return;
        }

        if (!$this->httpRequest->request->get('title')) {
            $this->errors[] = 'Veuillez remplir le champ titre';
        } elseif (!$this->httpRequest->request->get('content')) {
            $this->errors[] = 'Veuillez remplir le champ contenu';
        } elseif (!$this->httpRequest->request->get('chapo')) {
            $this->errors[] = 'Veuillez remplir le champ chapo';
        } else {
            // Nettoie et récupère les données du formulaire.
            $titre = ucfirst($this->cleanXSS($this->httpRequest->request->get('title')));
            $chapo = ucfirst($this->cleanXSS($this->httpRequest->request->get('chapo')));
            $contenu = ucfirst($this->cleanXSS($this->httpRequest->request->get('content')));

            // Met à jour les données du post.
            $this->updatePostData($titre, $chapo, $contenu, $id);

            // Redirige vers la page des détails du post.
            $this->redirect("/details-posts/$id");
            return;
        }

        // Choisi la template à appeler.
        $template = $this->twig->load('admin/edit.html');

        // Affiche la page avec la méthode render.
        $render = $template->render([
            'title' => 'Edition du post',
            'detailPost' => $detailPost[0],
            'errors' => $this->errors,
        ]);

        print_r($render);
    } // End editPost().

    
    /**
     * Vérifie le rôle de l'utilisateur pour l'édition et la suppression d'un post.
     *
     * Cette fonction vérifie si l'utilisateur actuel a le droit d'éditer ou de supprimer un post
     * en se basant sur son rôle et l'ID du post. Si l'utilisateur n'a pas les autorisations nécessaires,
     * il est redirigé vers une page d'erreur.
     *
     * @param string $userRole   Le rôle de l'utilisateur actuel.
     * @param int    $userId     L'identifiant de l'utilisateur actuel.
     * @param int    $postUserId L'identifiant de l'auteur du post.
     * @return void
     */
    private function checkRole($userRole, $userId, $postUserId)
    {
        // Vérifie si l'utilisateur a le rôle "admin" ou est l'auteur du post.
        if ($userRole === "admin" || $userId === $postUserId) {
            // L'utilisateur a les permissions nécessaires.
        } else {
            // Redirige vers une page d'erreur.
            $this->redirect('/error/');
            return;
        }
    } // End checkRole().


    /**
     * Vérifie si la session de l'utilisateur est active.
     *
     * Cette fonction vérifie si la session de l'utilisateur est active. Si l'utilisateur n'est pas connecté,
     * elle le redirige vers la page de connexion.
     *
     * @return void
     */
    private function checkSession()
    {
        // Vérifie si la session de l'utilisateur est active.
        if (!$this->httpSession->get('user')) {
            // Redirige vers la page de connexion.
            $this->redirect('/connexion/');
            return;
        }
    } // End checkSession().


    /**
     * Récupère les détails d'un post.
     *
     * Cette fonction récupère les détails d'un post à partir de son ID en utilisant la classe Post.
     *
     * @param int $id L'identifiant du post à récupérer.
     * @return array|null Un tableau contenant les détails du post ou null si aucun post n'est trouvé.
     */

    private function getPost($id)
    {
        // Récupère les détails du post avec l'ID donné.
        $post = new Post();
        $detailPost = $post->getPosts($id);
        return $detailPost;

    } // End getPost().


    /**
     * Met à jour les données d'un post.
     *
     * Cette fonction utilise la classe Post pour mettre à jour les données d'un post
     * en utilisant les nouvelles valeurs de titre, chapo et contenu.
     *
     * @param string $titre   Le nouveau titre du post.
     * @param string $chapo   Le nouveau chapo du post.
     * @param string $contenu Le nouveau contenu du post.
     * @param int    $id      L'identifiant du post à mettre à jour.
     * @return void
     */
    private function updatePostData($titre, $chapo, $contenu, $id)
    {
        // Met à jour les données du post.
        $post = new Post();
        $post->updatePost($titre, $chapo, $contenu, $id);
        
    } // End updatePostData().
} // End EditPostController().
