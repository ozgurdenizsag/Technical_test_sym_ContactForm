<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameDepartment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mailDepartment;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="departmentDestination")
     */
    private $colContact;

    public function __construct()
    {
        $this->colContact = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameDepartment(): ?string
    {
        return $this->nameDepartment;
    }

    public function setNameDepartment(string $nameDepartment): self
    {
        $this->nameDepartment = $nameDepartment;

        return $this;
    }

    public function getMailDepartment(): ?string
    {
        return $this->mailDepartment;
    }

    public function setMailDepartment(string $mailDepartment): self
    {
        $this->mailDepartment = $mailDepartment;

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getColContact(): Collection
    {
        return $this->colContact;
    }

    public function addColContact(Contact $colContact): self
    {
        if (!$this->colContact->contains($colContact)) {
            $this->colContact[] = $colContact;
            $colContact->setDepartmentDestination($this);
        }

        return $this;
    }

    public function removeColContact(Contact $colContact): self
    {
        if ($this->colContact->removeElement($colContact)) {
            // set the owning side to null (unless already changed)
            if ($colContact->getDepartmentDestination() === $this) {
                $colContact->setDepartmentDestination(null);
            }
        }

        return $this;
    }
}
