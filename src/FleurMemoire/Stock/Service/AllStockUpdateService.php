<?php
namespace FleurMemoire\Stock\Service;

use Doctrine\ORM\EntityManager;
use FleurMemoire\Item\Entity\ItemMaterial;
use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Item\Repository\ItemRepository;
use FleurMemoire\Item\Repository\MaterialRepository;
use FleurMemoire\Stock\Repository\DailyStockOfMaterialRepository;
use FleurMemoire\Stock\Service\Command\MaterialStockCalculateCommand;
use FleurMemoire\Stock\Service\Query\ItemStockQuery;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use JMS\DiExtraBundle\Annotation as DI;
use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Usecase\UsecaseInterface;

/**
 * 日別 在庫予定更新サービス
 *
 * @DI\Service()
 */
class AllStockUpdateService implements UsecaseInterface
{
    /**
     * @var MaterialStockCalculator
     */
    private $materialStockCalculator;
    /**
     * @var MaterialRepository
     */
    private $materialRepository;

    /**
     * @param MaterialStockCalculator $materialStockCalculator
     * @param MaterialRepository $materialRepository
     * @DI\InjectParams({
     *   "materialStockCalculator" = @DI\Inject("fleur_memoire.stock.service.material_stock_calculator"),
     *   "materialRepository" = @DI\Inject("fleurmemoire.item.material.repository")
     * })
     */
    public function __construct(MaterialStockCalculator $materialStockCalculator, MaterialRepository $materialRepository)
    {
        $this->materialStockCalculator = $materialStockCalculator;
        $this->materialRepository = $materialRepository;
    }

    /**
     * @param  EntityInterface $entity
     * @return mixed
     */
    public function run(EntityInterface $entity = null)
    {
        $materials = $this->materialRepository->findAll();

        foreach ($materials as $material)
        {
            $start = new Date('');
            /** @var Material $material */
            $term = new Term($start,
                $start->addDays($material->getStockCalcDays()));
            $command = new MaterialStockCalculateCommand($material, $term);
            $this->materialStockCalculator->run($command);
        }
    }
}
