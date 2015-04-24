<?php
namespace FleurMemoire\Purchase\Repository;

use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Repository\RepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class PurchaseRepository extends EntityRepository implements RepositoryInterface
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

    /**
     * @param Material $material
     * @param Date $date
     * @return int
     */
    public function findByMaterialAndArrivalDate(Material $material, Date $date)
    {
        $sql = 'select sum(purchase_purchase.qty) as arrival_qty ' .
            'from purchase_purchase ' .
            'where ' .
            ' purchase_purchase.Material_id = :material_id ' .
            ' and purchase_purchase.date = :date';

        $rsm = new Query\ResultSetMapping();
        $rsm->addScalarResult('arrival_qty', 'arrival_qty', 'integer');

        $nativeQuery = $this->_em->createNativeQuery($sql, $rsm);
        $nativeQuery->setParameters([
            ':material_id' => $material->getId(),
            ':date' => $date->format('Y-m-d'),
        ]);

        $result = $nativeQuery->getSingleResult();

        return $result['arrival_qty'] ?: 0;
    }
}
