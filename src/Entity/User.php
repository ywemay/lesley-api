<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @ApiResource(
 *   normalizationContext={"groups"={"user:read"}},
 *   denormalizationContext={"groups"={"user:write", "register"}},
 *     attributes={
 *      "security"="is_granted('ROLE_ADMIN')",
 *      "pagination_items_per_page"=30
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={
 *            "security"="is_granted('ROLE_ADMIN')",
 *            "validation_groups"={"Default", "create"}
 *          }
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"access_control"="is_granted('ROLE_USER') and object == user"},
 *         "delete"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ApiFilter(PropertyFilter::class)
 * @ApiFilter(SearchFilter::class, properties={"username" : "start"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Groups({"user:write", "user:read", "register"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Groups("user:write")
     * @Assert\NotBlank(groups={"create"})
     * @SerializedName("password")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SaleOrder", mappedBy="customer", orphanRemoval=true)
     */
    private $saleOrders;

    public function __construct()
    {
        $this->saleOrders = new ArrayCollection();
    }

    /**
     * @Groups({"saleorder:read", "user:read"})
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     * @Groups({"saleorder:read", "user:read"})
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @Groups({"user:write"})
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): string
    {
      return (string) $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return Collection|SaleOrder[]
     */
    public function getSaleOrders(): Collection
    {
        return $this->saleOrders;
    }

    public function addSaleOrder(SaleOrder $saleOrder): self
    {
        if (!$this->saleOrders->contains($saleOrder)) {
            $this->saleOrders[] = $saleOrder;
            $saleOrder->setCustomer($this);
        }

        return $this;
    }

    public function removeSaleOrder(SaleOrder $saleOrder): self
    {
        if ($this->saleOrders->contains($saleOrder)) {
            $this->saleOrders->removeElement($saleOrder);
            // set the owning side to null (unless already changed)
            if ($saleOrder->getCustomer() === $this) {
                $saleOrder->setCustomer(null);
            }
        }

        return $this;
    }
}
