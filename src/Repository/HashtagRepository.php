<?php

namespace App\Repository;

use App\Entity\Hashtag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Hashtag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hashtag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hashtag[]    findAll()
 * @method Hashtag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashtagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hashtag::class);
    }

    /**
     * @param $value
     * @return Hashtag[] Returns an array of Hashtag objects
     */

    public function findByText($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.text LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Hashtag
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
