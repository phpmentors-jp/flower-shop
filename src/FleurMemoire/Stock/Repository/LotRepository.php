<?php
namespace FleurMemoire\Stock\Repository;

use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Repository\RepositoryInterface;
use Doctrine\ORM\EntityRepository;

class LotRepository extends EntityRepository implements RepositoryInterface
{
    /**
     * @param EntityInterface $entity
     */
    public function add(EntityInterface $entity)
    {
        // TODO: Implement add() method.
    }

    /**
     * @param EntityInterface $entity
     */
    public function remove(EntityInterface $entity)
    {
        // TODO: Implement remove() method.
    }
}
