<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Student;
use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

//    /**
//     * @return Event[] Returns an array of Event objects
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

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

        /**
         * @return Event[]
        */
        public function findByTeacher(Teacher $teacher)
        {
            return $this->createQueryBuilder('e')
                ->andWhere('e.id_teacher = :teacher')
                ->setParameter('teacher', $teacher->getId())
                ->getQuery()
                ->getResult();
        }

        public function findByEleve(Student $student)
        {
            return $this->createQueryBuilder('e')
            ->innerJoin('e.groups', 'g')
            ->innerJoin('g.students', 's')
            ->where('s.id = :studentId')
            ->setParameter('studentId', $student->getId())
            ->getQuery()
            ->getResult();
        }
}
