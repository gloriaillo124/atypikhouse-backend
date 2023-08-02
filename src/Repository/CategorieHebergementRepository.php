<?php

namespace App\Repository;

use App\Entity\CategorieHebergement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieHebergement>
 *
 * @method CategorieHebergement|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieHebergement|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieHebergement[]    findAll()
 * @method CategorieHebergement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieHebergementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieHebergement::class);
    }

    public function save(CategorieHebergement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategorieHebergement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function updated(bool $flush = false): void
    {
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function findAllLimite(): void
    // {
    //     $this->getEntityManager()->find()
    // }

//    /**
//     * @return CategorieHebergementController[] Returns an array of CategorieHebergementController objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategorieHebergementController
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
