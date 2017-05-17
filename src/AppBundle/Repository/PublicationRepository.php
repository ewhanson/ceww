<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;

/**
 * PublicationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PublicationRepository extends EntityRepository {

    public function findPublication(Category $category, $title, $date = null, $placeName = null) {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.title = :title');
        $qb->setParameter('title', $title);
        $qb->andWhere('p.category = :category');
        $qb->setParameter('category', $category);

        if ($date) {
            $qb->innerJoin('p.dateYear', 'd');
            $qb->andWhere('d.value = :value');
            $qb->setParameter('value', $date);
        }
        if ($placeName) {
            $qb->innerJoin('p.location', 'l');
            $qb->andWhere('l.name = :place');
            $qb->setParameter('place', $placeName);
        } try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new Exception("Duplicate publication detected - " . implode(':', [$category, $title, $date, $placeName]));
        }
    }

}
