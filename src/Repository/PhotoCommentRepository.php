<?php

namespace App\Repository;

use App\Entity\PhotoComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PhotoComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotoComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotoComment[]    findAll()
 * @method PhotoComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoComment::class);
    }

    // /**
    //  * @return PhotoComment[] Returns an array of PhotoComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhotoComment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
