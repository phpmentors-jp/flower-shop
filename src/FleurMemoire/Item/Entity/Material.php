<?php
namespace FleurMemoire\Item\Entity;

use FleurMemoire\Purchase\Entity\MaterialLeadTime;
use FleurMemoire\Stock\Entity\MaterialDuration;
use PHPMentors\DomainKata\Entity\EntityInterface;

use Doctrine\ORM\Mapping As ORM;
use PHPMentors\DomainKata\Entity\Operation\EquatableInterface;

/**
 * 材料エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Item\Repository\MaterialRepository")
 * @ORM\Table(name="item_material")
 */
class Material implements EntityInterface, EquatableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 材料名
     * @var string
     * @ORM\Column(type="string", name="name", length=255, nullable=false)
     */
    private $name;

    /**
     * @var MaterialDuration
     * @ORM\OneToOne(targetEntity="FleurMemoire\Stock\Entity\MaterialDuration", mappedBy="Material")
     */
    private $Duration;

    /**
     * @var MaterialLeadTime
     * @ORM\OneToOne(targetEntity="FleurMemoire\Purchase\Entity\MaterialLeadTime", mappedBy="Material")
     */
    private $LeadTime;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return MaterialDuration
     */
    public function getDuration()
    {
        return $this->Duration;
    }

    /**
     * @return MaterialLeadTime
     */
    public function getLeadTime()
    {
        return $this->LeadTime;
    }

    /**
     * この単品の在庫計算日数
     * @return int
     */
    public function getStockCalcDays()
    {
        return max(
            $this->getDuration()->getDays(),
            $this->getLeadTime()->getDays()
        ) + 10;
    }

    /**
     * @param  EntityInterface $target
     * @return bool
     */
    public function equals(EntityInterface $target)
    {
        /** @var Material $target */
        assert($target instanceof Material);
        return $this->id === $target->id;
    }

}
