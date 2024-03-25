<?php

namespace App\Repository;

use App\Entity\VinylMix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<VinylMix>
 *
 * @method VinylMix|null find($id, $lockMode = null, $lockVersion = null)
 * @method VinylMix|null findOneBy(array $criteria, array $orderBy = null)
 * @method VinylMix[]    findAll()
 * @method VinylMix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinylMixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VinylMix::class);
    }

    public function add(VinylMix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VinylMix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return VinylMix[] Returns an array of VinylMix objects
    */
   public function findAllOrderdedByVotes(string $genre = null): array
   {
        // Line below is the same as: SELECT * FROM vinyl_mix AS mix ORDER BY votes DESC
        $queryBuilder = $this->createQueryBuilder('mix');
        
        if ($genre) {
            $queryBuilder
                ->andWhere('mix.genre = :genre') //Always use andWhere to avoid overriding the where clause
                ->setParameter('genre', $genre);
        }

        return  $queryBuilder->getQuery() // Query object
           ->getResult() // return array of objects
        //    ->getOneOrNullResult() // return one object
       ;
   }

   private function addOrderByVotesQueryBuilder(QueryBuilder $queryBuilder): QueryBuilder
   {   
        $queryBuilder = $queryBuilder ?? $this->createQueryBuilder('mix');
        return $queryBuilder->orderBy('mix.votes', 'DESC');
   }

//    public function findOneBySomeField($value): ?VinylMix
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
