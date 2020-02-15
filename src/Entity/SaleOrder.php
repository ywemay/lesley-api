<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get",
 *         "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"security"="is_granted('ROLE_ADMIN')"},
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SaleOrderRepository")
 */
class SaleOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="saleOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SaleOrderItem", mappedBy="saleOrder", orphanRemoval=true)
     */
    private $saleOrderItems;

    public function __construct()
    {
        $this->saleOrderItems = new ArrayCollection();
    }

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

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|SaleOrderItem[]
     */
    public function getSaleOrderItems(): Collection
    {
        return $this->saleOrderItems;
    }

    public function addSaleOrderItem(SaleOrderItem $saleOrderItem): self
    {
        if (!$this->saleOrderItems->contains($saleOrderItem)) {
            $this->saleOrderItems[] = $saleOrderItem;
            $saleOrderItem->setSaleOrder($this);
        }

        return $this;
    }

    public function removeSaleOrderItem(SaleOrderItem $saleOrderItem): self
    {
        if ($this->saleOrderItems->contains($saleOrderItem)) {
            $this->saleOrderItems->removeElement($saleOrderItem);
            // set the owning side to null (unless already changed)
            if ($saleOrderItem->getSaleOrder() === $this) {
                $saleOrderItem->setSaleOrder(null);
            }
        }

        return $this;
    }
}
