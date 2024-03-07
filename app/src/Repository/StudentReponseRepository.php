<?php

namespace App\Repository;

use App\Entity\StudentReponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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

    public function studentResponseExists($userId, $questionId) {
        $query = $this->createQueryBuilder('r')
            ->select('count(r.id)') // Comptez le nombre de réponses correspondantes
            ->innerJoin('r.student', 's') // Supposons que 'student' est votre référence à l'entité Student dans StudentReponse
            ->where('s.id = :userId') // Assurez-vous que c'est l'ID de l'étudiant, pas l'utilisateur si ce sont des entités séparées
            ->andWhere('r.id_question = :questionId') // Changez 'r.id_question' au champ correct dans votre entité StudentReponse
            ->setParameter('userId', $userId)
            ->setParameter('questionId', $questionId)
            ->getQuery()
            ->getSingleScalarResult(); // Récupère le résultat comme un nombre unique

        return $query > 0; // Retourne true si une réponse existe, false sinon
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
