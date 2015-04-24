<?php
namespace FleurMemoire\Stock\Service;

use Doctrine\ORM\EntityManager;
use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Item\Repository\ItemMaterialRepository;
use FleurMemoire\Purchase\Repository\PurchaseRepository;
use FleurMemoire\Stock\Repository\DailyStockOfMaterialRepository;
use FleurMemoire\Stock\Service\Command\MaterialStockCalculateCommand;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * 日別 単品在庫予定更新サービス
 *
 * @DI\Service()
 */
class MaterialStockCalculator implements UsecaseInterface
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
     * @var ItemMaterialRepository
     */
    private $itemMaterialRepository;
    /**
     * @var LotDailyTransitionCalculator
     */
    private $lotDailyTransitionCalculator;
    /**
     * @var DisposalCalculator
     */
    private $disposalCalculator;
    /**
     * @var PurchaseRepository
     */
    private $purchaseRepository;

    /**
     * @param EntityManager $entityManager
     * @param DailyStockOfMaterialRepository $dsmRepository
     * @param ItemMaterialRepository $itemMaterialRepository
     * @param LotDailyTransitionCalculator $lotDailyTransitionCalculator
     * @param DisposalCalculator $disposalCalculator
     * @param PurchaseRepository $purchaseRepository
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("entity_manager"),
     *  "dsmRepository" = @DI\Inject("fleurmemoire.stock.daily_stock_of_material.repository"),
     *  "itemMaterialRepository" = @DI\Inject("fleurmemoire.item.item_material.repository"),
     *  "lotDailyTransitionCalculator"=@DI\Inject("fleur_memoire.stock.service.lot_daily_transition_calculator"),
     *  "disposalCalculator"= @DI\Inject("fleur_memoire.stock.service.disposal_calculator"),
     *  "purchaseRepository"=@DI\Inject("fleurmemoire.purchase.purchase.repository")
     * })
     */
    public function __construct(EntityManager $entityManager, DailyStockOfMaterialRepository $dsmRepository, ItemMaterialRepository $itemMaterialRepository, LotDailyTransitionCalculator $lotDailyTransitionCalculator, DisposalCalculator $disposalCalculator, PurchaseRepository $purchaseRepository)
    {
        $this->dsmRepository = $dsmRepository;
        $this->entityManager = $entityManager;
        $this->itemMaterialRepository = $itemMaterialRepository;
        $this->lotDailyTransitionCalculator = $lotDailyTransitionCalculator;
        $this->disposalCalculator = $disposalCalculator;
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * @param  EntityInterface $entity
     * @return mixed
     */
    public function run(EntityInterface $entity)
    {
        /** @var MaterialStockCalculateCommand $entity */
        assert($entity instanceof MaterialStockCalculateCommand);

        $prevQty = null;
        foreach ($entity->term as $date)
        {
            /** @var Date $date */
            $prevQty = $this->calcForDate($entity->material, $date, $prevQty);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Material $material
     * @param Date $date
     * @return string
     */
    private function calcForDate(Material $material, Date $date, $prevQty)
    {
        $stock = $this->dsmRepository->getByMaterialAndDate($material, $date);

        if ($prevQty === null) {
            $qty = $stock->getStartQty();
        } else {
            $qty = $prevQty;
        }

        $stock->setStartQty($qty);

        // 出荷数
        $shipment = $this->getShipTotalQtyByDate($material, $date);
        $stock->setShipmentQty($shipment);

        // 入荷数
        $arrivalQty = $this->getArrivalQtyByDate($material, $date);
        $stock->setArrivalQty($arrivalQty);

        // ロット在庫更新
        $stockQty = $this->lotDailyTransitionCalculator->calc($material, $date, $shipment);

        // 廃棄数（ロット在庫更新後）
        $disposalQty = $this->disposalCalculator->calc($material, $date);
        $stock->setDisposalQty($disposalQty);

        // 当日残集計
        $stockQty = max($stockQty - $disposalQty, 0);
        $stock->setEndQty($stockQty);

        return $stockQty;
    }

    private function getShipTotalQtyByDate(Material $material, Date $date)
    {
        // 出荷予定＝注文　として数値を取得している
        return $this->itemMaterialRepository->findByMaterialAndShipDate($material, $date);
    }

    private function getArrivalQtyByDate(Material $material, Date $date)
    {
        // 入荷予定＝購入　として数値を取得している
        return $this->purchaseRepository->findByMaterialAndArrivalDate($material, $date);
    }
}
