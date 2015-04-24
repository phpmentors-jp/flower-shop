<?php

namespace FleurMemoire\Stock\Entity;

use Doctrine\ORM\Mapping As ORM;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;

/**
 * ロット別廃棄 エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Stock\Repository\DisposalRepository")
 * @ORM\Table(name="stock_disposal")
 */
class Disposal implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Lot
     * @ORM\OneToOne(targetEntity="FleurMemoire\Stock\Entity\Lot", inversedBy="Disposal")
     */
    private $Lot;

    /**
     * 廃棄日
     * @var Date
     * @ORM\Column(type="date", name="arrival_date", nullable=false)
     */
    private $date;

    /**
     * 数量
     * @var string
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=false, name="qty")
     */
    private $qty;

    /**
     * @param Lot $lot
     * @param Date $date
     * @param $qty
     */
    public function __construct(Lot $lot, Date $date, $qty)
    {
        $this->Lot = $lot;
        $this->date = $date;
        $this->qty = $qty;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Lot
     */
    public function getLot()
    {
        return $this->Lot;
    }

    /**
     * @param Lot $Lot
     */
    public function setLot($Lot)
    {
        $this->Lot = $Lot;
    }

    /**
     * @return Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param Date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param string $qty
     */
    public function setQty($qty)
    {
        $this->qty = $qty;
    }
}
