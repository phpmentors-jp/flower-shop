<?php
namespace FleurMemoire\Purchase\UseCase\Command;

use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;

class PurchaseAddCommand implements EntityInterface
{
    /**
     * @var Material
     */
    public $material;

    /**
     * @var Date
     */
    public $date;

    /**
     * @var int
     */
    public $qty;

    public function __construct(Material $material, Date $date, $qty)
    {
        $this->material = $material;
        $this->date = $date;
        $this->qty = $qty;
    }
}
