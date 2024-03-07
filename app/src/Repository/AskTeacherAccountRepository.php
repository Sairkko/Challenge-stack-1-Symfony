<?php

namespace App\Repository;

use App\Entity\AskTeacherAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AskTeacherAccount>
 *
 * @method AskTeacherAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method AskTeacherAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method AskTeacherAccount[]    findAll()
 * @method AskTeacherAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AskTeacherAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AskTeacherAccount::class);
    }

//    /**
//     * @return AskTeacherAccount[] Returns an array of AskTeacherAccount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AskTeacherAccount
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
