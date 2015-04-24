<?php
namespace FleurMemoire\Sales\Repository;

use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Repository\RepositoryInterface;
use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository implements RepositoryInterface
{
    /**
     * @param EntityInterface $entity
     */
    public function add(EntityInterface $entity)
    {
        $this->_em->persist($entity);
    }

    /**
     * @param EntityInterface $entity
     */
    public function remove(EntityInterface $entity)
    {
        // TODO: Implement remove() method.
    }
}
