<?php
namespace FleurMemoire\Purchase\Entity;

use PHPMentors\DomainKata\Entity\EntityInterface;

use Doctrine\ORM\Mapping As ORM;

/**
 * 仕入れ先 エンティティ
 * @ORM\Entity(repositoryClass="FleurMemoire\Purchase\Repository\SupplierRepository")
 * @ORM\Table(name="purchase_supplier")
 */
class Supplier implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 仕入れ先名
     * @var string
     * @ORM\Column(type="string", name="name", length=255, nullable=false)
     */
    private $name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}
