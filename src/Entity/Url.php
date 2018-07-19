<?php

namespace App\Entity;

use Doctrine\Common\Collections\{ArrayCollection,Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UrlRepository")
 */
class Url
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $long_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $short_url;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lifetime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StatisticalRecord", mappedBy="url")
     */
    private $statisticalrecord;

    public function __construct()
    {
        $this->statisticalrecord = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongUrl(): ?string
    {
        return $this->long_url;
    }

    public function setLongUrl(string $long_url): self
    {
        $this->long_url = $long_url;

        return $this;
    }

    public function getShortUrl(): ?string
    {
        return $this->short_url;
    }

    public function setShortUrl(string $short_url): self
    {
        $this->short_url = $short_url;

        return $this;
    }

    public function getLifetime(): ?\DateTimeInterface
    {
        return $this->lifetime;
    }

    public function setLifetime(?\DateTimeInterface $lifetime): self
    {
        $this->lifetime = $lifetime;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection|StatisticalRecord[]
     */
    public function getStatisticalrecord(): Collection
    {
        return $this->statisticalrecord;
    }

    public function addStatisticalrecord(StatisticalRecord $statisticalrecord): self
    {
        if (!$this->statisticalrecord->contains($statisticalrecord)) {
            $this->statisticalrecord[] = $statisticalrecord;
            $statisticalrecord->setUrl($this);
        }

        return $this;
    }

    public function removeStatisticalrecord(StatisticalRecord $statisticalrecord): self
    {
        if ($this->statisticalrecord->contains($statisticalrecord)) {
            $this->statisticalrecord->removeElement($statisticalrecord);
            // set the owning side to null (unless already changed)
            if ($statisticalrecord->getUrl() === $this) {
                $statisticalrecord->setUrl(null);
            }
        }

        return $this;
    }
}
