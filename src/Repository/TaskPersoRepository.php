<?php
// /src/Repository/TaskPersoRepository.php
namespace App\Repository;

use App\Entity\TaskPerso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TaskPerso|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskPerso|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskPerso[]    findAll()
 * @method TaskPerso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskPersoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskPerso::class);
    }

    // /**
    //  * @return TaskPerso[] Returns an array of TaskPerso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TaskPerso
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}