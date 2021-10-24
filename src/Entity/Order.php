<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(name="order_code", type="string", length=50)
     */
    private string $orderCode;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Unique
     */
    private int $userId;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTime $shippingDate = null;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTime $createdAt = null;

    public function __construct()
    {
        $this->setCreatedAt(is_null($this->getCreatedAt()) ? new \DateTime() : null);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    public function setOrderCode(string $orderCode): self
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    public function setShippingDate(?DateTime $shippingDate): void
    {
        $this->shippingDate = $shippingDate;
    }

    public function getShippingDate(): ?DateTime
    {
        return $this->shippingDate;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->createdAt = $created_at;
        return $this;
    }
}
