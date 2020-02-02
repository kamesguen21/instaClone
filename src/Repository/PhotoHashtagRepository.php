<?php

namespace App\Repository;

use App\Entity\PhotoHashtag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PhotoHashtag|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotoHashtag|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotoHashtag[]    findAll()
 * @method PhotoHashtag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoHashtagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoHashtag::class);
    }

    // /**
    //  * @return PhotoHashtag[] Returns an array of PhotoHashtag objects
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
    public function findOneBySomeField($value): ?PhotoHashtag
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
