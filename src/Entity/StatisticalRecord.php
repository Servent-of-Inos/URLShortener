<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatisticalRecordRepository")
 */
class StatisticalRecord
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    */
    private $id;

    /**
     * @ORM\Column(type="datetime")
    */
    private $timestamp;

    /**
     * @ORM\Column(type="text")
    */
    private $referrer;

    /**
     * @ORM\Column(type="text")
    */
    private $ip;

    /**
     * @ORM\Column(type="text")
    */
    private $browser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Url", inversedBy="statisticalrecord")
     * @ORM\JoinColumn(nullable=false)
     */
    private $url;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getReferrer(): ?string
    {
        return $this->referrer;
    }

    public function setReferrer(string $referrer): self
    {
        $this->referrer = $referrer;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function setBrowser(string $browser): self
    {
        $this->browser = $browser;

        return $this;
    }

    public function getUrl(): ?Url
    {
        return $this->url;
    }

    public function setUrl(?Url $url): self
    {
        $this->url = $url;

        return $this;
    }
}
