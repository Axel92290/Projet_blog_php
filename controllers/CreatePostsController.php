<?php

namespace Controllers;

use Models\Post;

class CreatePostsController extends BaseController
{


    /**
     * Gère la création d'un nouveau post.
     *
     * Cette méthode vérifie la session de l'utilisateur et le formulaire de soumission,
     * puis affiche la page de création de post.
     *
     * @return void
     */
    public function createPost()
    {
        $this->checkSession();
        $csrf = new \ParagonIE\AntiCSRF\AntiCSRF;

        if ($this->httpRequest->isMethod('POST') && $csrf->validateRequest()) {
            $this->checkFields(
                $this->httpRequest->request->get('title'),
                $this->httpRequest->request->get('chapo'),
                $this->httpRequest->request->get('content')
            );

            if (empty($this->errors)) {
                $titre = ucfirst($this->cleanXSS($this->httpRequest->request->get('title')));
                $contenu = ucfirst($this->cleanXSS($this->httpRequest->request->get('content')));
                $chapo = ucfirst($this->cleanXSS($this->httpRequest->request->get('chapo')));
                $idUser = $this->httpSession->get('user')['id'];
                $this->createNewPost($titre, $chapo, $contenu, $idUser);
                $this->redirect('/listing-posts/');
                return;
            } else {
                $this->errors[] = 'Veuillez remplir tous les champs';
            }
        }

        // On choisit la template à appeler.
        $template = $this->twig->load('admin/create.html');

        // Puis on affiche la page avec la méthode render.
        $render = $template->render([
                    'title' => 'Création d\'un post',
                    'errors' => $this->errors,
                  ]);
        print_r($render);

    } // End createPost().


    /**
     * Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion.
     *
     * @return void
     */
    private function checkSession()
    {
        if (!$this->httpSession->has('user')) {
            $this->redirect('/connexion/');
            return;
        }

    } // End checkSession().

    /**
     * Crée un nouveau post en utilisant les données fournies.
     *
     * @param string $titre    Le titre du post.
     * @param string $chapo    Le chapo du post.
     * @param string $contenu  Le contenu du post.
     * @param int    $idUser   L'ID de l'utilisateur créant le post.
     * @return void
     */
    private function createNewPost($titre, $chapo, $contenu, $idUser)
    {
        $post = new Post();
        $post->createPost($titre, $chapo, $contenu, $idUser);

    } // End createNewPost().

    /**
     * Vérifie les champs du formulaire pour s'assurer qu'ils ne sont pas vides.
     *
     * @param string $titre    Le titre du post.
     * @param string $chapo    Le chapo du post.
     * @param string $contenu  Le contenu du post.
     * @return void
     */
    private function checkFields($titre, $chapo, $contenu)
    {
        if (empty($titre)) {
            $this->errors[] = 'Veuillez remplir le champ titre';
        } elseif (empty($chapo)) {
            $this->errors[] = 'Veuillez remplir le champ chapo';
        } elseif (empty($contenu)) {
            $this->errors[] = 'Veuillez remplir le champ contenu';
        }
        
    } // End checkFields().
}
