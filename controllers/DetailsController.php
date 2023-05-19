<?php

namespace Controllers;

use Models\Post;

class DetailsController extends BaseController
{
    public function details($id)
    {

        // on choisi la template à appeler
        $template = $this->twig->load('details-posts/details.html');


        $post = new Post();
        $detailPost = $post->getDetailPost($id);


        // Puis on affiche la page avec la méthode render
        echo $template->render([
            'title' => 'Détail d\'un post',
            'detailPost' => $detailPost,
        ]);
    }
}
