<?php
namespace Controllers;

class DetailsController extends BaseController
{
    public function details()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('details-posts/details.html');

        // $post = new Post();
        // $listPost = $post->getPosts();


        // Puis on affiche la page avec la méthode render
        echo $template->render(['title' => 'Détail d\'un post']);
    }
}