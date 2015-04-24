<?php
namespace FleurMemoire\Stock\Repository;

use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Repository\RepositoryInterface;
use Doctrine\ORM\EntityRepository;

class DailyTransitionOfLotRepository extends EntityRepository implements RepositoryInterface
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
     * 単品ロット在庫推移で指定日のものを取得
     * @param Material $material
     * @param Date $date
     * @return mixed
     */
    public function findByMaterialAndDate(Material $material, Date $date)
    {
        $qb = $this->createQueryBuilder('dtl')
            ->leftJoin('dtl.Lot', 'l')
            ->where('l.Material = :material')
            ->andWhere('dtl.date = :date')
            ->setParameters([
                ':material' => $material,
                ':date' => $date,
            ]);

        return $qb->getQuery()->execute();
    }

    /**
     * 単品ロット在庫推移の最終日が指定日のものを取得
     * @param Material $material
     * @param Date $date
     * @return mixed
     */
    public function findLastByMaterialAndDate(Material $material, Date $date)
    {
        $qb = $this->createQueryBuilder('dtl')
            ->leftJoin('dtl.Lot', 'l')
            ->where('l.Material = :material')
            ->andWhere('dtl.date = :date')
            ->andWhere('dtl.date = l.eolDate')
            ->setParameters([
                ':material' => $material,
                ':date' => $date,
            ]);

        return $qb->getQuery()->execute();
    }
}
