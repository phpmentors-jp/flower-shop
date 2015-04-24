<?php
namespace FleurMemoire\Stock\Service\Query;

use FleurMemoire\Item\Entity\Item;
use FleurMemoire\Stock\Entity\DailyStockOfMaterial;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;

class ItemStockQuery implements EntityInterface
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
     * 指定日出荷可能限度数
     * @var int
     */
    public $availableMax = 0;

    /**
     * 各単品の在庫
     * @var DailyStockOfMaterial[]
     */
    public $stocks;

    public function __construct(Item $item, Date $date)
    {
        $this->item = $item;
        $this->date = $date;
    }
}
