<?php
namespace FleurMemoire\Stock\Repository;

use FleurMemoire\Stock\Entity\Disposal;
use FleurMemoire\Stock\Entity\Lot;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Repository\RepositoryInterface;
use Doctrine\ORM\EntityRepository;

class DisposalRepository extends EntityRepository implements RepositoryInterface
{
    /**
     * @param Lot $lot
     * @param $qty
     * @return Disposal
     */
    public function create(Lot $lot, $qty)
    {
        $disposal = new Disposal($lot, $lot->getEolDate(), $qty);

        $this->_em->persist($disposal);

        return $disposal;
    }


    /**
     * @param EntityInterface $entity
     */
    public function add(EntityInterface $entity)
    {
    }

    /**
     * @param EntityInterface $entity
     */
    public function remove(EntityInterface $entity = null)
    {
        if ($entity === null) return;
        $this->_em->remove($entity);
    }
}
