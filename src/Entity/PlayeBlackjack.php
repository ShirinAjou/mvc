<?php

namespace App\Entity;

use App\Repository\PlayeBlackjackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayeBlackjackRepository::class)]
class PlayeBlackjack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $score = null;

    #[ORM\Column]
    private ?bool $dealer = null;

    #[ORM\ManyToOne(inversedBy: 'playersingame')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameBlackJack $gamepl = null;

    #[ORM\Column(nullable: true)]
    private ?array $cardhand = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function isDealer(): ?bool
    {
        return $this->dealer;
    }

    public function setDealer(bool $dealer): static
    {
        $this->dealer = $dealer;

        return $this;
    }

    public function getGamepl(): ?GameBlackJack
    {
        return $this->gamepl;
    }

    public function setGamepl(?GameBlackJack $gamepl): static
    {
        $this->gamepl = $gamepl;

        return $this;
    }

    public function getCardhand(): ?array
    {
        return $this->cardhand;
    }

    public function setCardhand(?array $cardhand): static
    {
        $this->cardhand = $cardhand;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
