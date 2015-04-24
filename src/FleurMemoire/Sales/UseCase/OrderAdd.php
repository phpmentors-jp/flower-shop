<?php
namespace FleurMemoire\Sales\UseCase;

use Doctrine\ORM\EntityManager;
use FleurMemoire\Sales\Entity\Order;
use FleurMemoire\Sales\Repository\OrderRepository;
use FleurMemoire\Sales\UseCase\Command\OrderAddCommand;
use FleurMemoire\Stock\Service\AllStockUpdateService;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * 注文登録 ユースケース
 *
 * @DI\Service()
 */
class OrderAdd implements UsecaseInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var AllStockUpdateService
     */
    private $allStockUpdateService;

    /**
     * @param EntityManager $entityManager
     * @param OrderRepository $orderRepository
     * @param AllStockUpdateService $allStockUpdateService
     * @DI\InjectParams({
     *   "entityManager" = @DI\Inject("entity_manager"),
     *   "orderRepository" = @DI\Inject("fleurmemoire.sales.order.repository"),
     *   "allStockUpdateService" = @DI\Inject("fleur_memoire.stock.service.all_stock_update_service")
     * })
     */
    public function __construct(EntityManager $entityManager, OrderRepository $orderRepository, AllStockUpdateService $allStockUpdateService)
    {
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
        $this->allStockUpdateService = $allStockUpdateService;
    }

    /**
     * @param  EntityInterface $entity
     * @return mixed
     */
    public function run(EntityInterface $entity)
    {
        /** @var OrderAddCommand $entity */
        assert($entity instanceof OrderAddCommand);

        $order = new Order($entity->date, $entity->item, 1);

        $this->orderRepository->add($order);
        $this->entityManager->flush();

        $this->allStockUpdateService->run(null);
    }
}
