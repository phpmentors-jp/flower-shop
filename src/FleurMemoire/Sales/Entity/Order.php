<?php
namespace FleurMemoire\Sales\Entity;

use FleurMemoire\Item\Entity\Item;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;

use Doctrine\ORM\Mapping As ORM;

/**
 * 受注 エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Sales\Repository\OrderRepository")
 * @ORM\Table(name="sales_order")
 */
class Order implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="FleurMemoire\Item\Entity\Item", inversedBy="orders")
     */
    private $Item;

    /**
     * お届け日付
     * @var Date
     * @ORM\Column(type="date", name="date", nullable=false)
     */
    private $date;

    /**
     * 出荷日付
     * @var Date
     * @ORM\Column(type="date", name="date_of_shipment", nullable=false)
     */
    private $dateOfShipment;

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

    public function __construct(Date $date, Item $item, $qty)
    {
        $this->date = $date;
        $this->Item = $item;
        $this->qty = $qty;

        $this->dateOfShipment = $date->addDays(-1);

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
    public function getItem()
    {
        return $this->Item;
    }

    /**
     * @param mixed $Item
     */
    public function setItem($Item)
    {
        $this->Item = $Item;
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
     * @return Date
     */
    public function getDateOfShipment()
    {
        return $this->dateOfShipment;
    }

    /**
     * @param Date $dateOfShipment
     */
    public function setDateOfShipment($dateOfShipment)
    {
        $this->dateOfShipment = $dateOfShipment;
    }
}
