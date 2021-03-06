<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *   normalizationContext={"groups"={"read"}},
 *   denormalizationContext={"groups"={"write"}},
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
 * @ORM\Entity(repositoryClass="App\Repository\SaleOrderItemRepository")
 * @ApiFilter(SearchFilter::class, properties={"product": "exact"})
 * @ApiFilter(SearchFilter::class, properties={"saleOrder": "exact"})
 */
class SaleOrderItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read", "write"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read", "write"})
     */
    private $unit_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "write"})
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SaleOrder", inversedBy="saleOrderItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $saleOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @Groups("read")
     */
    public function getProductId(): ?int
    {
      return $this->getProduct()->getId();
    }

    /**
     * @Groups("read")
     */
    public function getProductName(): ?string
    {
      return $this->getProduct()->getName();
    }

    /**
     * @Groups("write")
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @Groups("read")
     */
    public function getUnitPrice(): ?int
    {
        return $this->unit_price;
    }

    /**
     * @Groups("write")
     */
    public function setUnitPrice(int $unit_price): self
    {
        $this->unit_price = intval($unit_price);
        $this->setTotalPrice($this->getQuantity() * $unit_price);
        return $this;
    }

    /**
     * @Groups("read")
     */
    public function getTotalPrice(): ?int
    {
        return $this->total_price;
    }

    public function setTotalPrice(int $total_price): self
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getSaleOrder(): ?SaleOrder
    {
        return $this->saleOrder;
    }

    /**
     * @Groups("read")
     */
    public function getSaleOrderId(): ?int
    {
        return $this->saleOrder->getId();
    }

    /**
     * @Groups("write")
     */
    public function setSaleOrder(?SaleOrder $saleOrder): self
    {
        $this->saleOrder = $saleOrder;

        return $this;
    }
}
