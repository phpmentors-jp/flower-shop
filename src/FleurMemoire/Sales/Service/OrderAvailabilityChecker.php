<?php
namespace FleurMemoire\Sales\Service;

use FleurMemoire\Sales\Service\Query\OrderAvailabilityQuery;
use FleurMemoire\Stock\Service\ItemStockService;
use FleurMemoire\Stock\Service\Query\ItemStockQuery;
use FleurMemoire\Util\Term;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service()
 */
class OrderAvailabilityChecker implements UsecaseInterface
{
    /**
     * @var ItemStockService
     */
    private $itemStockService;

    /**
     * @param ItemStockService $itemStockService
     * @DI\InjectParams({
     *   "itemStockService" = @DI\Inject("fleur_memoire.stock.service.item_stock_service")
     * })
     */
    public function __construct(ItemStockService $itemStockService)
    {
        $this->itemStockService = $itemStockService;
    }

    /**
     * @param  EntityInterface $entity
     * @return OrderAvailabilityQuery
     */
    public function run(EntityInterface $entity)
    {
        /** @var OrderAvailabilityQuery $entity */
        assert($entity instanceof OrderAvailabilityQuery);

        // 商品を構成する単品の中で最長のリードタイム
        $maxLeadTime = $entity->item->getMaxMaterialLeadTime();
        // 基準日起算でリードタイムまで
        $term = new Term($entity->date, $entity->baseDate->addDays($maxLeadTime));
        $entity->term = $term;

        $availables = [];
        foreach ($term as $date) {
            $query = new ItemStockQuery($entity->item, $date);
            /** @var ItemStockQuery $result */
            $result = $this->itemStockService->run($query);

            $availables[] = $result->availableMax;

            if ($date == $entity->date) {
                $entity->stocks = $result->stocks;
            }
        }

        $entity->availableMax = min($availables);
        $entity->maxLeadTime = $maxLeadTime;

        return $entity;
    }
}
