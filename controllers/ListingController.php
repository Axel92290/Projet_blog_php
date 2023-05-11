<?php
namespace Controllers;

use Models\Post;

class ListingController extends BaseController
{
    /**
     * @var string
     */
    private string $errors = '';
    
    public function listing()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('listing-posts/listing.html');

        
        $post = new Post();
        $listPost = $post->getPosts();


        // Puis on affiche la page avec la méthode render

        echo $template->render(['title' => 'Liste des posts']);

        echo $template->render([
            'title' => 'Liste des posts',
            'listPost' => $listPost,
            'errors' => $this->errors
        ]);



    }
}