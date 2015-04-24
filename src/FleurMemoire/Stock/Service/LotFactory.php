<?php
namespace FleurMemoire\Stock\Service;

use Doctrine\ORM\EntityManager;
use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Purchase\Entity\Purchase;
use FleurMemoire\Stock\Entity\DailyTransitionOfLot;
use FleurMemoire\Stock\Entity\Lot;
use FleurMemoire\Stock\Repository\LotRepository;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * ロット登録
 *
 * @DI\Service()
 */
class LotFactory implements UsecaseInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var LotRepository
     */
    private $lotRepository;

    /**
     * @param EntityManager $entityManager
     * @param LotRepository $lotRepository
     * @DI\InjectParams({
     *   "entityManager" = @DI\Inject("entity_manager"),
     *   "lotRepository" = @DI\Inject("fleurmemoire.stock.lot.repository")
     * })
     */
    public function __construct(EntityManager $entityManager, LotRepository $lotRepository)
    {
        $this->entityManager = $entityManager;
        $this->lotRepository = $lotRepository;
    }

    /**
     * @param  EntityInterface $entity
     * @return mixed
     */
    public function run(EntityInterface $entity = null)
    {
        /** @var Purchase $entity */
        assert($entity instanceof Purchase);

        /** @var Material $material */
        $material = $entity->getMaterial();
        $lot = new Lot($material, $entity->getDate(), $entity->getQty());
        $lot->setEolDate($entity->getDate()->addDays($material->getDuration()->getDays()));

        $term = $lot->getLifeTerm();
        $dailyTransition = null;
        foreach ($term as $date)
        {
            $dailyTransition = new DailyTransitionOfLot($lot, $date, $dailyTransition);
            $lot->addDailyTransition($dailyTransition);
        }

        $this->entityManager->persist($lot);
        $this->entityManager->flush();

        return $lot;
    }
}
