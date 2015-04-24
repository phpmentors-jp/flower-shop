<?php
namespace FleurMemoire\Item\Entity;

use PHPMentors\DomainKata\Entity\EntityInterface;

use Doctrine\ORM\Mapping As ORM;
use PHPMentors\DomainKata\Entity\Operation\EquatableInterface;

/**
 * 商品-材料 構成 エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Item\Repository\ItemMaterialRepository")
 * @ORM\Table(name="item_item_material")
 */
class ItemMaterial implements EntityInterface, EquatableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="FleurMemoire\Item\Entity\Item", inversedBy="itemMaterials")
     */
    private $Item;

    /**
     * @ORM\ManyToOne(targetEntity="FleurMemoire\Item\Entity\Material", inversedBy="itemMaterials")
     */
    private $Material;

    /**
     * 数
     * @var int
     * @ORM\Column(type="integer", name="number", nullable=false)
     */
    private $number;

    public function __construct(Item $item, Material $material, $number)
    {
        $this->Item = $item;
        $this->Material = $material;
        $this->number = $number;
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
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @param  EntityInterface $target
     * @return bool
     */
    public function equals(EntityInterface $target)
    {
        /** @var ItemMaterial $target */
        assert($target instanceof ItemMaterial);
        return $this->id === $target->id;
    }
}
