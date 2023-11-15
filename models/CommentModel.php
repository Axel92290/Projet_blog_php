<?php

namespace Models;

use PDO;

class CommentModel
{
    // Propriétés de la classe    
    /**
     * id
     *
     * @var mixed
     */
    private $id;
    /**
     * contenu
     *
     * @var mixed
     */
    private $contenu;
    /**
     * dateCreation
     *
     * @var mixed
     */
    private $dateCreation;
    /**
     * dateModification
     *
     * @var mixed
     */
    private $dateModification;
    /**
     * idUser
     *
     * @var mixed
     */
    private $idUser;
    /**
     * idPost
     *
     * @var mixed
     */
    private $idPost;
    /**
     * statut
     *
     * @var mixed
     */
    private $statut;

    /**
     * adminPage
     *
     * @var mixed
     */
    private $adminPage;


    // Getters et Setters

    // Getter pour l'ID
    public function getId()
    {
        return $this->id;
    }

    // Setter pour l'ID
    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter pour le contenu
    public function getContenu()
    {
        return $this->contenu;
    }

    // Setter pour le contenu
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

    // Getter pour la date de création
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    // Setter pour la date de création
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    // Getter pour la date de modification
    public function getDateModification()
    {
        return $this->dateModification;
    }

    // Setter pour la date de modification
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    }

    // Getter pour l'ID de l'utilisateur
    public function getIdUser()
    {
        return $this->idUser;
    }

    // Setter pour l'ID de l'utilisateur
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    // Getter pour l'ID du post
    public function getIdPost()
    {
        return $this->idPost;
    }

    // Setter pour l'ID du post
    public function setIdPost($idPost)
    {
        $this->idPost = $idPost;
    }

    // Getter pour le statut
    public function getStatut()
    {
        return $this->statut;
    }

    // Setter pour le statut
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    // Getter pour la page d'administration
    public function getAdminPage()
    {
        return $this->adminPage;
    }

    // Setter pour la page d'administration
    public function setAdminPage($adminPage)
    {
        $this->adminPage = $adminPage;
    }
}
?>