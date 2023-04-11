<?php

class ListingController extends BaseController
{
    public function listing()
    {

        // on choisi la template à appeler
        $template = $this->twig->load('listing-posts/listing.html');


        // Puis on affiche la page avec la méthode render
        echo $template->render(['title' => 'Liste des posts']);
    }
}