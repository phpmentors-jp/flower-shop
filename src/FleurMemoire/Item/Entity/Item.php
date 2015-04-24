<?php
namespace FleurMemoire\Item\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FleurMemoire\Sales\Entity\Order;
use PHPMentors\DomainKata\Entity\EntityInterface;

use Doctrine\ORM\Mapping As ORM;

/**
 * 商品エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Item\Repository\ItemRepository")
 * @ORM\Table(name="item_item")
 */
class Item implements EntityInterface
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
     * 単価
     * @var string
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=false, name="price")
     */
    private $price;

    /**
     * 商品の材料構成
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="FleurMemoire\Item\Entity\ItemMaterial", mappedBy="Item")
     */
    private $itemMaterials;

    /**
     * 商品に関連する注文
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="FleurMemoire\Sales\Entity\Order", mappedBy="Item", fetch="EXTRA_LAZY")
     */
    private $orders;


    public function __construct()
    {
        $this->itemMaterials = new ArrayCollection();
        $this->orders = new ArrayCollection();
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
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param Material $material
     * @param $number
     */
    public function addMaterial(Material $material, $number)
    {
        $this->itemMaterials->add(new ItemMaterial($this, $material, $number));
    }

    /**
     * @return ArrayCollection|ItemMaterial[]
     */
    public function getItemMaterials()
    {
        return $this->itemMaterials;
    }

    /**
     * @param Material $material
     * @return ItemMaterial|null
     */
    public function getItemMaterial(Material $material)
    {
        foreach ($this->itemMaterials as $itemMaterial) {
            if ($itemMaterial->getMaterial()->equals($material)) {
                return $itemMaterial;
            }
        }

        return null;
    }

    /**
     * @return ArrayCollection|Order[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * 構成単品の最長のリードタイム
     *
     * @return int
     */
    public function getMaxMaterialLeadTime()
    {
        $lt = [];
        foreach ($this->itemMaterials as $itemMaterial)
        {
            $lt[] = $itemMaterial->getMaterial()->getLeadTime()->getDays();
        }

        return max($lt);
    }
}
