<?php
namespace Controllers;

use Models\Post;

class ListingController extends BaseController
{


    /**
     * Affiche la page de listing des posts.
     *
     * Cette fonction affiche la page de listing des posts.
     * Elle permet à un utilisateur de voir la liste des posts.
     * @return void
     * 
     */
    public function listing()
    {

        // On choisi la template à appeler.
        $template = $this->twig->load('listing-posts/listing.html');

        $post = new Post();
        $listPosts = $post->getPosts();


        // Puis on affiche la page avec la méthode render.
        $render = $template->render([
            'title'     => 'Liste des posts',
            'listPosts' => $listPosts,
        ]);        

        print_r($render);
    }
}