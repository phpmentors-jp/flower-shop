<?php
namespace FleurMemoire\Sales\Service\Query;

use FleurMemoire\Item\Entity\Item;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use PHPMentors\DomainKata\Entity\EntityInterface;

class OrderAvailabilityQuery implements EntityInterface
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
     * @var Date
     */
    public $baseDate;
    /**
     * @var int
     */
    public $availableMax;
    /**
     * @var int
     */
    public $maxLeadTime;
    /**
     * @var
     */
    public $stocks;
    /**
     * @var Term
     */
    public $term;

    public function __construct(Item $item, Date $date, Date $baseDate)
    {
        $this->item = $item;
        $this->date = $date;
        $this->baseDate = $baseDate;
    }
}
