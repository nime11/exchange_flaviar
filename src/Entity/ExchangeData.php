<?php

namespace App\Entity;

use App\Repository\ExchangeDataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExchangeDataRepository::class)
 */
class ExchangeData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $currency_from;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $currency_to;

    /**
     * @ORM\Column(type="float")
     */
    private $value_from;

    /**
     * @ORM\Column(type="float")
     */
    private $value_to;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCurrencyFrom(): ?string
    {
        return $this->currency_from;
    }

    public function setCurrencyFrom(string $currency_from): self
    {
        $this->currency_from = $currency_from;

        return $this;
    }

    public function getCurrencyTo(): ?string
    {
        return $this->currency_to;
    }

    public function setCurrencyTo(string $currency_to): self
    {
        $this->currency_to = $currency_to;

        return $this;
    }

    public function getValueFrom(): ?float
    {
        return $this->value_from;
    }

    public function setValueFrom(float $value_from): self
    {
        $this->value_from = $value_from;

        return $this;
    }

    public function getValueTo(): ?float
    {
        return $this->value_to;
    }

    public function setValueTo(float $value_to): self
    {
        $this->value_to = $value_to;

        return $this;
    }
}
