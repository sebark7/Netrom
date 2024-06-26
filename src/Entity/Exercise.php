<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    /**
     * @var Collection<int, ExerciseLog>
     */
    #[ORM\OneToMany(targetEntity: ExerciseLog::class, mappedBy: 'exercise', orphanRemoval: true)]
    private Collection $exerciseLogs;

    public function __construct()
    {
        $this->exerciseLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, ExerciseLog>
     */
    public function getExerciseLogs(): Collection
    {
        return $this->exerciseLogs;
    }

    public function addExerciseLog(ExerciseLog $exerciseLog): static
    {
        if (!$this->exerciseLogs->contains($exerciseLog)) {
            $this->exerciseLogs->add($exerciseLog);
            $exerciseLog->setExercise($this);
        }

        return $this;
    }

    public function removeExerciseLog(ExerciseLog $exerciseLog): static
    {
        if ($this->exerciseLogs->removeElement($exerciseLog)) {
            // set the owning side to null (unless already changed)
            if ($exerciseLog->getExercise() === $this) {
                $exerciseLog->setExercise(null);
            }
        }

        return $this;
    }
}
