<?php

namespace App\Entity;

use App\Repository\ContactsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactsRepository::class)]
class Contacts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir un prénom')]
    #[Assert\Length(
        min: 3,
        minMessage: 'Le prénom doit faire au moins {{ limit }} caractères',
        max: 30,
        maxMessage: 'Le prénom ne peut faire plus de {{ limit }} caractères'
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir un nom')]
    #[Assert\Length(
        min: 3,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères',
        max: 30,
        maxMessage: 'Le nom ne peut faire plus de {{ limit }} caractères'
    )]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(message: 'Le mail {{ value }} n\'est pas valide')]
    #[Assert\NotBlank(message: 'Veuillez saisir un e-mail')]
    #[Assert\Length(
        min: 5,
        minMessage: 'Le mail doit faire au moins {{ limit }} caractères',
        max: 30,
        maxMessage: 'Le mail ne peut faire plus de {{ limit }} caractères'
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez saisir un sujet')]
    #[Assert\Length(
        min: 3,
        minMessage: 'Le sujet doit faire au moins {{ limit }} caractères',
        max: 30,
        maxMessage: 'Le sujet ne peut faire plus de {{ limit }} caractères'
    )]
    private ?string $subject = null;

    #[ORM\Column(length: 1000)]
    #[Assert\NotBlank(message: 'Veuillez saisir un contenu')]
    #[Assert\Length(
        min: 5,
        minMessage: 'Le contenu du mail doit faire au moins {{ limit }} caractères',
        max: 1000,
        maxMessage: 'Le contenu du mail ne peut faire plus de {{ limit }} caractères'
    )]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }
}
