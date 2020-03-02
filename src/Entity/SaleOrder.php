<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *   normalizationContext={"groups"={"read"}},
 *   denormalizationContext={"groups"={"write"}},
 *   attributes={"security"="is_granted('ROLE_USER')"},
 *   collectionOperations={
 *     "get",
 *     "post"={"security"="is_granted('ROLE_ADMIN')"}
 *   },
 *   itemOperations={
 *     "get"={
 *        "normalization_context"={"groups"={"read","extended"}}
 *      },
 *     "put"={"security"="is_granted('ROLE_ADMIN')"},
 *   }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\SaleOrderRepository")
 */
class SaleOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("read")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"write"})
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="saleOrders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"extended", "write"})
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

    /**
     * @Groups("read")
     */
    public function getOrderDate(): ?string
    {
      return $this->date->format('Y-m-d');
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

    /**
     * @Groups("read")
     */
    public function getCustomerId(): ?int
    {
      return $this->getCustomer()->getId();
    }

    /**
     * @Groups("read")
     */
    public function getCustomerName(): ?String
    {
      return $this->getCustomer()->getUsername();
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
