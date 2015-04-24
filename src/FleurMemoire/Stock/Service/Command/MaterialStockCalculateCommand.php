<?php
namespace FleurMemoire\Stock\Service\Command;

use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Util\Term;
use PHPMentors\DomainKata\Entity\EntityInterface;

class MaterialStockCalculateCommand implements EntityInterface
{
    /**
     * @var Material
     */
    public $material;

    /**
     * @var Term
     */
    public $term;

    public function __construct(Material $material, Term $term)
    {
        $this->material = $material;
        $this->term = $term;
    }
}
