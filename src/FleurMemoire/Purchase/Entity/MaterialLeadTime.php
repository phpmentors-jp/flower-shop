<?php
namespace FleurMemoire\Purchase\Entity;

use FleurMemoire\Util\Date;
use PHPMentors\DomainKata\Entity\EntityInterface;
use FleurMemoire\Item\Entity\Material;

use Doctrine\ORM\Mapping As ORM;

/**
 * 材料 リードタイム エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Purchase\Repository\MaterialLeadTimeRepository")
 * @ORM\Table(name="purchase_material_leadtime")
 */
class MaterialLeadTime implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="FleurMemoire\Item\Entity\Material", inversedBy="MaterialLeadTime")
     */
    private $Material;

    /**
     * @ORM\ManyToOne(targetEntity="FleurMemoire\Purchase\Entity\Supplier", inversedBy="materialLeadTimes")
     */
    private $Supplier;

    /**
     * 日数
     * @var int
     * @ORM\Column(type="integer", name="days", nullable=false)
     */
    private $days;

    public function __construct(Supplier $supplier, Material $material, $days)
    {
        $this->Supplier = $supplier;
        $this->Material = $material;
        $this->days = $days;
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
    public function getMaterial()
    {
        return $this->Material;
    }

    /**
     * @return mixed
     */
    public function getSupplier()
    {
        return $this->Supplier;
    }

    /**
     * @return mixed
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param mixed $days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }

    /**
     * 指定した日付が基準日から起算してリードタイム日数以降か？
     * @param Date $targetDate
     * @param Date $baseDate
     * @return bool
     */
    public function isPurchaseableDate(Date $targetDate, Date $baseDate)
    {
        /** @var \DateInterval $diff */
        $diff = $targetDate->diff($baseDate);

        return $diff->days >= $this->days;
    }
}
