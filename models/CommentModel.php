<?php

namespace Models;

class CommentModel
{
    private int $id;
    private string $contenu;
    private \DateTime $dateCreation;
    private \DateTime $dateModification;
    private int $idUser;
    private int $idPost;
    private string $statut;
    private bool $adminPage;

    // Getters et Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function getDateCreation(): \DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getDateModification(): \DateTime
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTime $dateModification): void
    {
        $this->dateModification = $dateModification;
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): void
    {
        $this->idUser = $idUser;
    }

    public function getIdPost(): int
    {
        return $this->idPost;
    }

    public function setIdPost(int $idPost): void
    {
        $this->idPost = $idPost;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getAdminPage(): bool
    {
        return $this->adminPage;
    }

    public function setAdminPage(bool $adminPage): void
    {
        $this->adminPage = $adminPage;
    }
}
