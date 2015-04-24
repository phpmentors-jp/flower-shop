<?php
namespace FleurMemoire\Sales\UseCase\Command;

use FleurMemoire\Item\Entity\Item;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;

class OrderAddCommand implements EntityInterface
{
    /**
     * @var Item
     */
    public $item;

    /**
     * @var Date
     */
    public $date;

    /**
     * @var int
     */
    public $qty;

    public function __construct(Item $item, Date $date, $qty)
    {
        $this->item = $item;
        $this->date = $date;
        $this->qty = $qty;
    }
}
