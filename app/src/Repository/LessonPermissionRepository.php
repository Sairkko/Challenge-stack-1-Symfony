<?php

namespace App\Repository;

use App\Entity\LessonPermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LessonPermission>
 *
 * @method LessonPermission|null find($id, $lockMode = null, $lockVersion = null)
 * @method LessonPermission|null findOneBy(array $criteria, array $orderBy = null)
 * @method LessonPermission[]    findAll()
 * @method LessonPermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonPermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LessonPermission::class);
    }

//    /**
//     * @return LessonPermission[] Returns an array of LessonPermission objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LessonPermission
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
