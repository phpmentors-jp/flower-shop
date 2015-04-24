<?php
namespace FleurMemoire\Purchase\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FleurMemoire\Item\Entity\Material;
use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;

use Doctrine\ORM\Mapping As ORM;

/**
 * 購買 エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Purchase\Repository\PurchaseRepository")
 * @ORM\Table(name="purchase_purchase")
 */
class Purchase implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 希望納品日
     * @var Date
     * @ORM\Column(type="date", name="date", nullable=false)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="FleurMemoire\Item\Entity\Material", inversedBy="purchases")
     */
    private $Material;

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

    public function __construct(Date $date, Material $material, $qty)
    {
        $this->date = $date;
        $this->Material = $material;
        $this->qty = $qty;

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
}
