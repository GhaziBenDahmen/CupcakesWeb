<?php

namespace ECommerceBundle\Repository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends \Doctrine\ORM\EntityRepository
{
    public function findTags()
    {
        $query=$this->getEntityManager()->createQuery("Select DISTINCT t.name from ECommerceBundle:Tag t ")
        ;
        return $query->getResult();
    }
}
