<?php

namespace App\Repository;

use App\Entity\Exercise;
use App\Entity\MuscleGroup;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercise>
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    public function saveExercise(Exercise $exercise): void
    {
        $this->getEntityManager()->persist($exercise);
        $this->getEntityManager()->flush();
    }

    public function updateExercise(Exercise $exercise): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($exercise);
        $entityManager->flush();
    }

    public function findById(int $id): ?Exercise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteById(int $id): void
    {
        $entityManager = $this->getEntityManager();
        $exercise = $this->find($id);

        if ($exercise) {
            $entityManager->remove($exercise);
            $entityManager->flush();
        }
    }

    public function findType(string $name, int $id) : ?Exercise
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.name = :val')
            ->andWhere('m.id <> :Id')
            ->setParameter('val', $name)
            ->setParameter('Id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Exercise[] Returns an array of Exercise objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Exercise
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
