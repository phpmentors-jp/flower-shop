<?php
namespace FleurMemoire\Stock\Service;

use Doctrine\ORM\EntityManager;
use FleurMemoire\Item\Entity\ItemMaterial;
use FleurMemoire\Item\Repository\ItemRepository;
use FleurMemoire\Stock\Repository\DailyStockOfMaterialRepository;
use FleurMemoire\Stock\Service\Query\ItemStockQuery;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * 日別 商品在庫予定 問い合わせサービス
 *
 * @DI\Service()
 */
class ItemStockService implements UsecaseInterface
{
    /**
     * @var DailyStockOfMaterialRepository
     */
    private $dsmRepository;
    /**
     * @var Term
     */
    private $term;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @param EntityManager $entityManager
     * @param DailyStockOfMaterialRepository $dsmRepository
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("entity_manager"),
     *  "itemRepository" = @DI\Inject("fleurmemoire.item.item.repository"),
     *  "dsmRepository" = @DI\Inject("fleurmemoire.stock.daily_stock_of_material.repository")
     * })
     */
    public function __construct(EntityManager $entityManager, ItemRepository $itemRepository, DailyStockOfMaterialRepository $dsmRepository)
    {
        $this->dsmRepository = $dsmRepository;
        $this->entityManager = $entityManager;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param  EntityInterface $entity
     * @return ItemStockQuery
     */
    public function run(EntityInterface $entity)
    {
        /** @var ItemStockQuery $entity */
        assert($entity instanceof ItemStockQuery);

        // 単品ごとに在庫を調べる
        $itemMaterials = $entity->item->getItemMaterials();
        $limits = [];
        $stocks = [];
        foreach ($itemMaterials as $itemMaterial)
        {
            /** @var ItemMaterial $itemMaterial */
            $stock = $this->dsmRepository->getByMaterialAndDate($itemMaterial->getMaterial(), $entity->date);

            $limits[] = $stock->buildableLimit($entity->item);
            $stocks[$itemMaterial->getMaterial()->getId()] = $stock;
        }

        $entity->availableMax = min($limits);
        $entity->stocks = $stocks;

        return $entity;
    }
}
