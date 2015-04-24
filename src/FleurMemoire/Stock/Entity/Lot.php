<?php
namespace FleurMemoire\Stock\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Util\Date;
use FleurMemoire\Util\Term;
use PHPMentors\DomainKata\Entity\EntityInterface;

use Doctrine\ORM\Mapping As ORM;

/**
 * 在庫ロット エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Stock\Repository\LotRepository")
 * @ORM\Table(name="stock_lot")
 */
class Lot implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    //private $Arrival;     入荷との関連

    /**
     * 入荷日
     * @var Date
     * @ORM\Column(type="date", name="arrival_date", nullable=false)
     */
    private $arrivalDate;

    /**
     * 品質維持期限切れ日
     * @var Date
     * @ORM\Column(type="date", name="eol_date", nullable=false)
     */
    private $eolDate;

    /**
     * @var Material
     * @ORM\ManyToOne(targetEntity="FleurMemoire\Item\Entity\Material", inversedBy="lots")
     */
    private $Material;

    /**
     * @var Disposal
     * @ORM\OneToOne(targetEntity="FleurMemoire\Stock\Entity\Disposal", mappedBy="Lot")
     */
    private $Disposal;

    /**
     * 数量
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
     * @ORM\OneToMany(targetEntity="FleurMemoire\Stock\Entity\DailyTransitionOfLot", mappedBy="Lot", cascade={"all"})
     */
    private $dailyTransitions;

    /**
     * @param Material $material
     * @param Date $arrivalDate
     * @param $qty
     */
    public function __construct(Material $material, Date $arrivalDate, $qty)
    {
        $this->Material = $material;
        $this->arrivalDate = $arrivalDate;
        $this->qty = $qty;

        $this->createdAt = new \DateTime();

        $this->dailyTransitions = new ArrayCollection();
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
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * @param Date $arrivalDate
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;
    }

    /**
     * @return Date
     */
    public function getEolDate()
    {
        if ($this->eolDate instanceof \DateTime) {
            $this->eolDate = Date::createFromDateTime($this->eolDate);
        }
        return $this->eolDate;
    }

    /**
     * @param Date $eolDate
     */
    public function setEolDate($eolDate)
    {
        if ($eolDate instanceof \DateTime) {
            $eolDate = Date::createFromDateTime($eolDate);
        }
        $this->eolDate = $eolDate;
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
     * @return Term
     */
    public function getLifeTerm()
    {
        return new Term($this->arrivalDate, $this->eolDate);
    }

    /**
     * @return mixed
     */
    public function getDailyTransitions()
    {
        return $this->dailyTransitions;
    }

    /**
     * @param mixed $dailyTransitions
     */
    public function setDailyTransitions($dailyTransitions)
    {
        $this->dailyTransitions = $dailyTransitions;
    }

    /**
     * @param DailyTransitionOfLot $dailyTransition
     */
    public function addDailyTransition(DailyTransitionOfLot $dailyTransition)
    {
        $this->dailyTransitions->add($dailyTransition);
    }

    /**
     * @return Disposal
     */
    public function getDisposal()
    {
        return $this->Disposal;
    }

    /**
     * @param Disposal $Disposal
     */
    public function setDisposal($Disposal)
    {
        $this->Disposal = $Disposal;
    }
}
