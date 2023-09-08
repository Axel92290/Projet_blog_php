<?php

namespace Controllers;

class LogoutController extends BaseController
{

    
    /**
     * Gère la déconnexion de l'utilisateur.
     *
     * Cette méthode vérifie la session de l'utilisateur et le formulaire de soumission,
     * puis affiche la page de déconnexion.
     *
     * @return void
     */
    public function logout()
    {
        $this->httpSession->invalidate();
        $this->httpSession->clear();
        $this->redirect('/');
        return;
        
    } // End logout().
}
