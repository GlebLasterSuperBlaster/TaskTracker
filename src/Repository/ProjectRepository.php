<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface $paginator
     * https://github.com/KnpLabs/KnpPaginatorBundle
     */
    private $paginator;
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Project::class);
        $this->paginator = $paginator;
    }
//
//    public function __construct(RegistryInterface $registry)
//    {
//        parent::__construct($registry, Project::class);
//    }

    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findAllSorted(int $userId, int $page, ?string $sortBy)
    {
        switch ($sortBy) {
            case "created_projects":
                $dbQuery = $this->createQueryBuilder('p')
                    ->leftJoin('p.createdBy', 'createdBy')
                    ->andWhere('createdBy = (:val)')
                    ->setParameter('val', $userId)
                    ->orderBy('p.createdAt', 'DESC');
                break;
            case "invited_to_projects":
                $dbQuery = $this->createQueryBuilder('p')
                    ->leftJoin('p.invitedUsers', 'invitedUsers')
                    ->andWhere('invitedUsers = (:val)')
                    ->setParameter('val', $userId)
                    ->orderBy('p.createdAt', 'DESC');
                break;
            default:
                $dbQuery = $this->createQueryBuilder('p')
                    ->leftJoin('p.createdBy', 'createdBy')
                    ->leftJoin('p.invitedUsers', 'invitedUsers')
                    ->andWhere('createdBy = (:val)')
                    ->orWhere('invitedUsers = (:val)')
                    ->setParameter('val', $userId)
                    ->orderBy('p.createdAt', 'DESC');
                break;

        }

        $pagination = $this->paginator->paginate($dbQuery, $page, 15);
        return $pagination;
    }

}
