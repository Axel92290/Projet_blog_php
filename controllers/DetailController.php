<?php

class DetailController extends BaseController
{
    public function index()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('details-posts/detail.html');

        // $post = new Post();
        // $listPost = $post->getPosts();


        // Puis on affiche la page avec la méthode render
        echo $template->render(['title' => 'Détail d\'un post']);
    }
}