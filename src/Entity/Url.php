<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=255)
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

    public function getId()
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
}
