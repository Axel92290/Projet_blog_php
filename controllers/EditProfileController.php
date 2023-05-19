<?php

namespace Controllers;

use Models\Users;

class EditProfileController extends BaseController
{

    /**
     * @var string
     */
    private array $errors = [];



    public function editProfile()
    {


        // on choisi la template à appeler
        $template = $this->twig->load('edit-profile/edit-profile.html');

        $this->checkSession();

        if (!empty($_POST)) {

            $facebook = trim($_POST['facebook']);
            $twitter = trim($_POST['twitter']);
            $instagram = trim($_POST['instagram']);
            $linkedin = trim($_POST['linkedin']);
            $github = trim($_POST['github']);
            $catchPhrase = trim($_POST['catchPhrase']);
            $cv = $_POST['cv'];
            $id = $_SESSION['user']['id'];

            $user = new Users();
            $udpateProfile = $user->updateProfile($facebook, $twitter, $instagram, $linkedin, $github, $catchPhrase, $cv, $id);


            if ($udpateProfile) {
                header('Location: /');
            }
        }
        



        echo $template->render([
            'title' => 'Modification du profil',
            'errors' => $this->errors,

        ]);
    }

    private function checkSession()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion/');
            exit;
        }
    }
}
