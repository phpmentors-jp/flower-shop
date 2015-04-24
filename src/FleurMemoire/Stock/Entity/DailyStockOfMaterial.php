<?php

namespace FleurMemoire\Stock\Entity;

use FleurMemoire\Item\Entity\Item;
use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;
use Doctrine\ORM\Mapping As ORM;

/**
 * 日別 材料在庫 エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Stock\Repository\DailyStockOfMaterialRepository")
 * @ORM\Table(name="stock_daily_stock_of_material")
 */
class DailyStockOfMaterial implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="FleurMemoire\Item\Entity\Material")
     */
    private $Material;

    /**
     * 日付
     * @var Date
     * @ORM\Column(type="date", name="date", nullable=false)
     */
    private $date;

    /**
     * 前日残
     * @var string
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=false, name="start_qty")
     */
    private $startQty;

    /**
     * 当日入荷
     * @var string
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=false, name="arrival_qty")
     */
    private $arrivalQty;

    /**
     * 当日出荷
     * @var string
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=false, name="shipment_qty")
     */
    private $shipmentQty;

    /**
     * 当日廃棄
     * @var string
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=false, name="disposal_qty")
     */
    private $disposalQty;

    /**
     * 当日残
     * @var string
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=false, name="end_qty")
     */
    private $endQty;

    public function __construct(Material $material, Date $date)
    {
        $this->Material = $material;
        $this->date = $date;
        $this->startQty = 0;
        $this->arrivalQty = 0;
        $this->shipmentQty = 0;
        $this->disposalQty = 0;
        $this->endQty = 0;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Date
     */
    public function getDate()
    {
        return new Date($this->date->format('Y-m-d'));
    }

    /**
     * @param Date $date
     */
    public function setDate(Date $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getStartQty()
    {
        return $this->startQty;
    }

    /**
     * @param string $startQty
     */
    public function setStartQty($startQty)
    {
        $this->startQty = $startQty;
    }

    /**
     * @return string
     */
    public function getArrivalQty()
    {
        return $this->arrivalQty;
    }

    /**
     * @param string $arrivalQty
     */
    public function setArrivalQty($arrivalQty)
    {
        $this->arrivalQty = $arrivalQty;
    }

    /**
     * @return string
     */
    public function getShipmentQty()
    {
        return $this->shipmentQty;
    }

    /**
     * @param string $shipmentQty
     */
    public function setShipmentQty($shipmentQty)
    {
        $this->shipmentQty = $shipmentQty;
    }

    /**
     * @return string
     */
    public function getDisposalQty()
    {
        return $this->disposalQty;
    }

    /**
     * @param string $disposalQty
     */
    public function setDisposalQty($disposalQty)
    {
        $this->disposalQty = $disposalQty;
    }

    /**
     * @return string
     */
    public function getEndQty()
    {
        return $this->endQty;
    }

    /**
     * @param string $endQty
     */
    public function setEndQty($endQty)
    {
        $this->endQty = $endQty;
    }

    /**
     * @return mixed
     */
    public function getMaterial()
    {
        return $this->Material;
    }

    /**
     * @param mixed $Material
     */
    public function setMaterial($Material)
    {
        $this->Material = $Material;
    }

    /**
     * 当日在庫で構成可能な商品数
     * @param Item $item
     * @return int
     */
    public function buildableLimit(Item $item)
    {
        return floor($this->endQty / $item->getItemMaterial($this->Material)->getNumber());
    }
}
