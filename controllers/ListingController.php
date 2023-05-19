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
        $getDatas = $post->getDatas();

        var_dump($getDatas);


        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Liste des posts',
            'listPosts' => $listPosts,
            'getDatas' => $getDatas


        ]);



    }
}