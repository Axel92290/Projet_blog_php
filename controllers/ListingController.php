<?php

class ListingController extends BaseController
{
    private string $errors = '';

    public function listing()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('listing-posts/listing.html');


        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Liste des posts',
            'errors' => $this->errors
        ]);


        $post = new Post();
        $listPost = $post->getPosts();
    }
}