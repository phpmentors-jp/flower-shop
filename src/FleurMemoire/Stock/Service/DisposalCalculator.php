<?php
namespace FleurMemoire\Stock\Service;

use Doctrine\ORM\EntityManager;
use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Stock\Entity\DailyTransitionOfLot;
use FleurMemoire\Stock\Entity\Lot;
use FleurMemoire\Stock\Repository\DailyTransitionOfLotRepository;
use FleurMemoire\Stock\Repository\DisposalRepository;
use FleurMemoire\Util\Date;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * ロット在庫廃棄更新サービス
 *
 * @DI\Service()
 */
class DisposalCalculator
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var DisposalRepository
     */
    private $disposalRepository;
    /**
     * @var DailyTransitionOfLotRepository
     */
    private $dailyTransitionOfLotRepository;

    /**
     * @param EntityManager $entityManager
     * @param DisposalRepository $disposalRepository
     * @param DailyTransitionOfLotRepository $dailyTransitionOfLotRepository
     * @DI\InjectParams({
     *  "entityManager" = @DI\Inject("entity_manager"),
     *  "disposalRepository" = @DI\Inject("fleurmemoire.stock.disposal.repository"),
     *  "dailyTransitionOfLotRepository" = @DI\Inject("fleurmemoire.stock.daily_transition_of_lot.repository")
     * })
     */
    public function __construct(EntityManager $entityManager, DisposalRepository $disposalRepository, DailyTransitionOfLotRepository $dailyTransitionOfLotRepository)
    {
        $this->entityManager = $entityManager;
        $this->disposalRepository = $disposalRepository;
        $this->dailyTransitionOfLotRepository = $dailyTransitionOfLotRepository;
    }

    public function calc(Material $material, Date $date)
    {
        $transitions = $this->dailyTransitionOfLotRepository->findLastByMaterialAndDate($material, $date);

        $total = 0;
        foreach ($transitions as $transition)
        {
            /** @var DailyTransitionOfLot $transition */
            /** @var Lot $lot */
            $lot = $transition->getLot();
            $qty = $transition->getQty();
            if ($qty > 0)
            {
                if (($disposal = $lot->getDisposal()) === null) {
                    $this->disposalRepository->create($lot, $qty);
                } else {
                    $disposal->setQty($qty);
                }
                $total += $qty;
            } else {
                $this->disposalRepository->remove($lot->getDisposal());
            }
        }

        $this->entityManager->flush();

        return $total;
    }
}
