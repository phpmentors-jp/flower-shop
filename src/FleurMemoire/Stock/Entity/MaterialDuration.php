<?php
namespace FleurMemoire\Stock\Entity;

use PHPMentors\DomainKata\Entity\EntityInterface;
use FleurMemoire\Item\Entity\Material;

use Doctrine\ORM\Mapping As ORM;

/**
 * 材料 品質保持期限 エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Stock\Repository\MaterialDurationRepository")
 * @ORM\Table(name="stock_material_duration")
 */
class MaterialDuration implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="FleurMemoire\Item\Entity\Material", inversedBy="Duration")
     */
    private $Material;

    /**
     * 品質保持日数
     * @var int
     * @ORM\Column(type="integer", name="number", nullable=false)
     */
    private $days;

    public function __construct(Material $material, $days)
    {
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
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @param int $days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }
}
