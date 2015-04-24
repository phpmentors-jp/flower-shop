<?php
namespace FleurMemoire\Item\Repository;

use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Repository\RepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ItemMaterialRepository extends EntityRepository implements RepositoryInterface
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

    /**
     * @param Material $material
     * @param Date $date
     * @return mixed
     */
    public function findByMaterialAndShipDate(Material $material, Date $date)
    {
        $sql = 'select item_item_material.number * sum(sales_order.qty) as ship_qty ' .
            'from item_item_material ' .
            ' left join item_item on item_item.id = item_item_material.Item_id ' .
            ' left join sales_order on sales_order.Item_id = item_item.id ' .
            'where ' .
            ' item_item_material.Material_id = :material_id ' .
            ' and sales_order.date_of_shipment = :date';

        $rsm = new Query\ResultSetMapping();
        $rsm->addScalarResult('ship_qty', 'ship_qty', 'integer');

        $nativeQuery = $this->_em->createNativeQuery($sql, $rsm);
        $nativeQuery->setParameters([
            ':material_id' => $material->getId(),
            ':date' => $date->format('Y-m-d'),
        ]);

        $result = $nativeQuery->getSingleResult();

        return $result['ship_qty'] ?: 0;
    }
}
