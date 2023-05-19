<?php
namespace Controllers;

use Models\Post;

class ListingController extends BaseController
{
    private string $errors = '';

    public function listing()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('listing-posts/listing.html');

        
        $post = new Post();
        $listPosts = $post->getPosts();
        // var_dump($listPosts);


        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Liste des posts',
            'listPosts' => $listPosts

        ]);



    }
}