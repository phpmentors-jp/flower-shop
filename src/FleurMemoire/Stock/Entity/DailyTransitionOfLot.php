<?php
namespace FleurMemoire\Stock\Entity;

use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;

use Doctrine\ORM\Mapping As ORM;

/**
 * 在庫ロットの日別推移 エンティティ
 *      在庫ロットの入荷日から品質維持期限までの推移
 * @ORM\Entity(repositoryClass="FleurMemoire\Stock\Repository\DailyTransitionOfLotRepository")
 * @ORM\Table(name="stock_daily_transition_of_lot")
 */
class DailyTransitionOfLot implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    //private $Arrival;     入荷との関連

    /**
     * @var Lot
     * @ORM\ManyToOne(targetEntity="FleurMemoire\Stock\Entity\Lot", inversedBy="dailyTransitions")
     */
    private $Lot;

    /**
     * 対象日
     * @var Date
     * @ORM\Column(type="date", name="date", nullable=false)
     */
    private $date;

    /**
     * 数量（当日残）
     * @var string
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=false, name="qty")
     */
    private $qty;

    /**
     * 登録日時
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    private $createdAt;

    /**
     * 更新日時
     * @var \DateTime
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    private $updatedAt;

    /**
     * @var DailyTransitionOfLot
     * @ORM\OneToOne(targetEntity="FleurMemoire\Stock\Entity\DailyTransitionOfLot", inversedBy="next")
     */
    private $prev;

    /**
     * @var DailyTransitionOfLot
     * @ORM\OneToOne(targetEntity="FleurMemoire\Stock\Entity\DailyTransitionOfLot", mappedBy="prev")
     */
    private $next;

    /**
     * @param Lot $lot
     * @param Date $date
     * @param DailyTransitionOfLot $prev
     */
    public function __construct(Lot $lot, Date $date, DailyTransitionOfLot $prev = null)
    {
        $this->Lot = $lot;
        $this->date = $date;
        $this->prev = $prev;
        $this->qty = 0;

        $this->createdAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLot()
    {
        return $this->Lot;
    }

    /**
     * @param mixed $Lot
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return DailyTransitionOfLot
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * @param DailyTransitionOfLot $prev
     */
    public function setPrev($prev)
    {
        $this->prev = $prev;
    }

    /**
     * @return DailyTransitionOfLot
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @param DailyTransitionOfLot $next
     */
    public function setNext($next)
    {
        $this->next = $next;
    }

    /**
     * @param $qty 未処理出荷数
     * @return 未処理出荷数
     */
    public function apply($qty)
    {
        // 前日残
        if ($this->prev) {
            $prevQty = $this->prev->qty;
        } else {
            $prevQty = $this->Lot->getQty();    // 最初の日ならロットの初期数
        }

        // 当日残
        $this->qty = max($prevQty - $qty, 0);

        // 未処理出荷数を返す
        return max($qty - $this->qty, 0);
    }
}
