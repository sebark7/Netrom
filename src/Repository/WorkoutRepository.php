<?php

namespace App\Repository;

use App\Entity\Exercise;
use App\Entity\User;
use App\Entity\Workout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workout>
 */
class WorkoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workout::class);
    }

    public function saveWorkout(Workout $workout): void
    {
        $this->getEntityManager()->persist($workout);
        $this->getEntityManager()->flush();
    }


    public function showWorkoutUserId(int $id): ?User
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.person = :user')
            ->setParameter('user', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Workout[] Returns an array of Workout objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Workout
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
