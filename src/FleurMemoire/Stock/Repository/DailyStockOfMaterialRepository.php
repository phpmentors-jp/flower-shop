<?php
namespace FleurMemoire\Stock\Repository;

use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Stock\Entity\DailyStockOfMaterial;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Repository\RepositoryInterface;
use Doctrine\ORM\EntityRepository;

class DailyStockOfMaterialRepository extends EntityRepository implements RepositoryInterface
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
     * @param Term $term
     * @return mixed
     */
    public function findByMaterialAndTerm(Material $material, Term $term)
    {
        $qb = $this->createQueryBuilder('dsm')
            ->where('dsm.Material = :material')
            ->andWhere('dsm.date >= :start')
            ->andWhere('dsm.date <= :end')
            ->setParameters([
                ':material' => $material,
                ':start' => $term->getStart(),
                ':end' => $term->getEnd()
            ]);

        return $qb->getQuery()->execute();
    }

    /**
     * @param Material $material
     * @param Date $date
     * @return DailyStockOfMaterial
     */
    public function getByMaterialAndDate(Material $material, Date $date)
    {
        $qb = $this->createQueryBuilder('dsm')
            ->where('dsm.Material = :material')
            ->andWhere('dsm.date = :date')
            ->setParameters([
                ':material' => $material,
                ':date' => $date,
            ]);

        $stock = $qb->getQuery()->getOneOrNullResult();

        if ($stock === null) {
            $stock = new DailyStockOfMaterial($material, $date);
            $this->add($stock);
        }

        return $stock;
    }
}
