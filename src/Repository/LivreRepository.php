<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    /**
     * @return Livre[] Returns an array of Livre objects
     */
    public function findBySearch($value)
    {
        return $this->createQueryBuilder('l')
            ->where('l.titre LIKE :val')
            ->orWhere('l.auteur LIKE :val')
            ->setParameter('val', "%$value%")
            ->orderBy('l.titre', 'ASC')
            ->addOrderBy('l.auteur')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }

    public function findByAvailable()
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.emprunts', 'e')
            ->where('e.date_retour IS NULL')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
    */
}
