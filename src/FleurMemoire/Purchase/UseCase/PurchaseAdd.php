<?php
namespace FleurMemoire\Purchase\UseCase;

use Doctrine\ORM\EntityManager;
use FleurMemoire\Purchase\Entity\Purchase;
use FleurMemoire\Purchase\Repository\PurchaseRepository;
use FleurMemoire\Purchase\UseCase\Command\PurchaseAddCommand;
use FleurMemoire\Stock\Service\AllStockUpdateService;
use FleurMemoire\Stock\Service\LotFactory;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service()
 */
class PurchaseAdd implements UsecaseInterface
{
    /**
     * @var PurchaseRepository
     */
    private $purchaseRepository;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var AllStockUpdateService
     */
    private $allStockUpdateService;
    /**
     * @var LotFactory
     */
    private $lotFactory;

    /**
     * @param EntityManager $entityManager
     * @param PurchaseRepository $purchaseRepository
     * @param AllStockUpdateService $allStockUpdateService
     * @param LotFactory $lotFactory
     * @DI\InjectParams({
     *   "entityManager" = @DI\Inject("entity_manager"),
     *   "purchaseRepository" = @DI\Inject("fleurmemoire.purchase.purchase.repository"),
     *   "allStockUpdateService" = @DI\Inject("fleur_memoire.stock.service.all_stock_update_service"),
     *   "lotFactory" = @DI\Inject("fleur_memoire.stock.service.lot_factory")
     * })
     */
    public function __construct(EntityManager $entityManager, PurchaseRepository $purchaseRepository, AllStockUpdateService $allStockUpdateService, LotFactory $lotFactory)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->entityManager = $entityManager;
        $this->allStockUpdateService = $allStockUpdateService;
        $this->lotFactory = $lotFactory;
    }

    /**
     * @param  EntityInterface $entity
     * @return mixed
     */
    public function run(EntityInterface $entity)
    {
        /** @var PurchaseAddCommand $entity */
        assert($entity instanceof PurchaseAddCommand);

        $purchase = new Purchase($entity->date, $entity->material, $entity->qty);
        $this->purchaseRepository->add($purchase);

        $this->lotFactory->run($purchase);      // TODO イベント化
        $this->allStockUpdateService->run(null);    // TODO イベント化
    }
}
