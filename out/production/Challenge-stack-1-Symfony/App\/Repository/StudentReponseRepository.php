<?php

namespace App\Repository;

use App\Entity\StudentReponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentReponse>
 *
 * @method StudentReponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentReponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentReponse[]    findAll()
 * @method StudentReponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentReponse::class);
    }

//    /**
//     * @return StudentReponse[] Returns an array of StudentReponse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StudentReponse
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
