<?php

namespace App\Entity;

use App\Repository\GameBlackJackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameBlackJackRepository::class)]
class GameBlackJack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    /**
     * @var Collection<int, PlayeBlackjack>
     */
    #[ORM\OneToMany(targetEntity: PlayeBlackjack::class, mappedBy: 'gamepl')]
    private Collection $playersingame;

    public function __construct()
    {
        $this->playersingame = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    /**
     * @return Collection<int, PlayeBlackjack>
     */
    public function getPlayersingame(): Collection
    {
        return $this->playersingame;
    }

    public function addPlayersingame(PlayeBlackjack $playersingame): static
    {
        if (!$this->playersingame->contains($playersingame)) {
            $this->playersingame->add($playersingame);
            $playersingame->setGamepl($this);
        }

        return $this;
    }

    public function removePlayersingame(PlayeBlackjack $playersingame): static
    {
        if ($this->playersingame->removeElement($playersingame)) {
            // set the owning side to null (unless already changed)
            if ($playersingame->getGamepl() === $this) {
                $playersingame->setGamepl(null);
            }
        }

        return $this;
    }
}
