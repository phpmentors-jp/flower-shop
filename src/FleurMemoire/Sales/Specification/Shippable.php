<?php
namespace FleurMemoire\Sales\Specification;

use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Specification\SpecificationInterface;

class Shippable implements SpecificationInterface
{
    /**
     * @param  EntityInterface $entity
     * @return bool
     */
    public function isSatisfiedBy(EntityInterface $entity)
    {
        // TODO: Implement isSatisfiedBy() method.
    }
}
