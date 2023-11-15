<?php

namespace Models;

use PDO;

/**
 * Class PostModel
 */
class PostModel
{
    /**
     * @var int The post ID.
     */
    private $id;

    /**
     * @var string The post title.
     */
    private $titre;

    /**
     * @var string The post summary.
     */
    private $chapo;

    /**
     * @var string The post content.
     */
    private $contenu;

    /**
     * @var DateTime The date and time the post was created.
     */
    private $dateCreation;

    /**
     * @var DateTime The date and time the post was last modified.
     */
    private $dateModification;

    /**
     * @var int The ID of the user who created the post.
     */
    private $idUser;

    /**
     * Get the post ID.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the post ID.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the post title.
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set the post title.
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * Get the post summary.
     * @return string
     */
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * Set the post summary.
     * @param string $chapo
     */
    public function setChapo($chapo)
    {
        $this->chapo = $chapo;
    }

    /**
     * Get the post content.
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set the post content.
     * @param string $contenu
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

    /**
     * Get the date and time the post was created.
     * @return DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set the date and time the post was created.
     * @param DateTime $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * Get the date and time the post was last modified.
     * @return DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set the date and time the post was last modified.
     * @param DateTime $dateModification
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    }

    /**
     * Get the ID of the user who created the post.
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the ID of the user who created the post.
     * @param int $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }
}
?>