<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }

    // retourne la liste des question filtré par titre
    public function findByTitle($title){

        $query = $this->createQueryBuilder('q')
                        ->where('q.title LIKE :searchTitle')
                        ->setParameter('searchTitle', '%' . $title . '%')
                        ->orderBy('q.title', 'ASC'); 

        return $query->getQuery()->getResult();
    }

    // retourne la liste des question avec le nombre de réponse
    public function findAllQuestion(){

        $query = $this->createQueryBuilder('q')
                        // ->innerjoin('q.answers', 'a')
                        // ->innerjoin('q.user', 'u')
                        ->orderBy('q.rank', 'DESC'); 

        return $query->getQuery()->getResult();
    }
}
