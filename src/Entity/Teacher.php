<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="teacher", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    /**
     * @ORM\OneToMany(targetEntity=Visit::class, mappedBy="teacher")
     */
    private $visits;

    public function __construct()
    {
        $this->visits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(User $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return Collection|Visit[]
     */
    public function getVisit(): Collection
    {
        return $this->visits;
    }

    public function addVisits(Visit $visits): self
    {
        if (!$this->visits->contains($visits)) {
            $this->visits[] = $visits;
            $visits->setTeacher($this);
        }

        return $this;
    }

    public function removeVisits(Visit $visits): self
    {
        if ($this->visits->removeElement($visits)) {
            // set the owning side to null (unless already changed)
            if ($visits->getTeacher() === $this) {
                $visits->setTeacher(null);
            }
        }

        return $this;
    }
}
