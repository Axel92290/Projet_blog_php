<?php
namespace Controllers;

use Models\Post;

class ListingController extends BaseController
{

    public function listing()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('listing-posts/listing.html');

        
        $post = new Post();
        $listPosts = $post->getPosts();


        // Puis on affiche la page avec la méthode render
       
        $render = $template->render([
            'title' => 'Liste des posts',
            'listPosts' => $listPosts,


        ]);

        print_r($render);


    }
}