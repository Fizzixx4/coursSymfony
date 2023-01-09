<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conference>
 *
 * @method Conference|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conference|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conference[]    findAll()
 * @method Conference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function save(Conference $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conference $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAfterYear($year){
        return $this->createQueryBuilder('conf')
            ->andWhere('conf.year >= :val')//Pour éviter les injections SQL
            ->setParameter('val', $year)
            ->orderBy('conf.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public const CONF_PER_PAGE = 3;

    public function getPaginator(int $offset, string $year, string $city) :Paginator{
        $query = $this->createQueryBuilder('conf');
        if($year !== ''){
            $query->andWhere('conf.year = :year')
            ->setParameter('year', $year);
        }
        if($city !== ''){
            $query->andWhere('conf.city = :city')
                ->setParameter('city', $city);
        }
        $query->orderBy('conf.year', 'DESC')
            ->addOrderBy('conf.city', 'ASC')
            ->setMaxResults(self::CONF_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();

            return new Paginator($query);
    }

    /**
     * @return Array Returns list of year for conference 
    */
    public function getListYear(){
        $years = [];
        foreach ($this->createQueryBuilder('conf')
            ->select('conf.year')
            ->distinct(true)
            ->orderBy('conf.year', 'ASC')
            ->getQuery()
            ->getResult() as $col){
                $years[] = $col['year'];
       }
       return $years;
    }

    /**
     * Liste des villes
     *
     * @return Array
     */
    public function getListCities(){
        $cities = [];
        foreach ($this->createQueryBuilder('conf')
            ->select('conf.city')
            ->distinct(true)
            ->orderBy('conf.city', 'ASC')
            ->getQuery()
            ->getResult() as $col){
                $cities[] = $col['city'];
       }
       return $cities;
    }

//    /**
//     * @return Conference[] Returns an array of Conference objects
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

//    public function findOneBySomeField($value): ?Conference
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
