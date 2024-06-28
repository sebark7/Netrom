<?php

namespace App\Entity;

use App\Repository\MuscleGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MuscleGroupRepository::class)]
class MuscleGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $tipul = null;

    /**
     * @var Collection<int, Exercise>
     */
    #[ORM\OneToMany(targetEntity: Exercise::class, mappedBy: 'muscleGroup')]
    private Collection $exercise_id;

    public function __construct()
    {
        $this->exercise_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipul(): ?string
    {
        return $this->tipul;
    }

    public function setTipul(string $tipul): static
    {
        $this->tipul = $tipul;

        return $this;
    }

    /**
     * @return Collection<int, Exercise>
     */
    public function getExerciseId(): Collection
    {
        return $this->exercise_id;
    }

    public function addExerciseId(Exercise $exerciseId): static
    {
        if (!$this->exercise_id->contains($exerciseId)) {
            $this->exercise_id->add($exerciseId);
            $exerciseId->setMuscleGroup($this);
        }

        return $this;
    }

    public function removeExerciseId(Exercise $exerciseId): static
    {
        if ($this->exercise_id->removeElement($exerciseId)) {
            // set the owning side to null (unless already changed)
            if ($exerciseId->getMuscleGroup() === $this) {
                $exerciseId->setMuscleGroup(null);
            }
        }

        return $this;
    }
}
