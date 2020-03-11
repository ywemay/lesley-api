<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

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
 *     "get",
 *     "put"={"security"="is_granted('ROLE_ADMIN')"},
 *   }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ApiFilter(PropertyFilter::class)
 * @ApiFilter(SearchFilter::class, properties={"name" : "partial"})
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=160)
     */
    private $name;

    /**
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     */
    public $photo;

    /**
     * @ORM\Column(type="integer")
     */
    private $sell_price;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $barcode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @Groups({"read"})
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups({"read"})
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @Groups({"write"})
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @Groups({"read"})
     */
    public function getSellPrice(): ?int
    {
        return $this->sell_price;
    }

    /**
     * @Groups({"write"})
     */
    public function setSellPrice(int $sell_price): self
    {
        $this->sell_price = $sell_price;

        return $this;
    }

    /**
     * @Groups({"read"})
     */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /**
     * @Groups({"write"})
     */
    public function setBarcode(?string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * @Groups({"read"})
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * @Groups({"write"})
     */
    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}
