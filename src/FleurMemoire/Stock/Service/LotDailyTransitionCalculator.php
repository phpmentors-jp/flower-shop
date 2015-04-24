<?php
namespace FleurMemoire\Stock\Service;

use Doctrine\ORM\EntityManager;
use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Stock\Entity\DailyTransitionOfLot;
use FleurMemoire\Stock\Repository\DailyTransitionOfLotRepository;
use FleurMemoire\Util\Date;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * 日別 ロット在庫推移更新サービス
 *
 * @DI\Service()
 */
class LotDailyTransitionCalculator
{
    /**
     * @var DailyTransitionOfLotRepository
     */
    private $dailyTransitionOfLotRepository;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     * @param DailyTransitionOfLotRepository $dailyTransitionOfLotRepository
     * @DI\InjectParams({
     *  "dailyTransitionOfLotRepository" = @DI\Inject("fleurmemoire.stock.daily_transition_of_lot.repository")
     * })
     */
    public function __construct(EntityManager $entityManager, DailyTransitionOfLotRepository $dailyTransitionOfLotRepository)
    {
        $this->dailyTransitionOfLotRepository = $dailyTransitionOfLotRepository;
        $this->entityManager = $entityManager;
    }

    public function calc(Material $material, Date $date, $shipmentQty)
    {
        $transitions = $this->dailyTransitionOfLotRepository->findByMaterialAndDate($material, $date);
        $stockQty = 0;

        foreach ($transitions as $transition)
        {
            /** @var DailyTransitionOfLot $transition */
            $shipmentQty = $transition->apply($shipmentQty);
            $stockQty += $transition->getQty();
        }

        $this->entityManager->flush();

        return $stockQty;
    }
}
