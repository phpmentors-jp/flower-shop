<?php
namespace FleurMemoire\Util;

class Date extends \DateTimeImmutable
{
    static public function createFromDateTime(\DateTime $from)
    {
        return new Date($from->format('Y-m-d'));
    }

    public function __construct($dateStr)
    {
        $tmpDate = new \DateTime($dateStr);
        parent::__construct( $tmpDate->format('Y-m-d 00:00:00'));
    }

    public function __toString()
    {
        return $this->format('Y-m-d');
    }

    /**
     * @param $days
     * @return Date
     */
    public function addDays($days)
    {
        if ($days < 0) {
            return new Date($this->sub(new \DateInterval(sprintf('P%sD', -1 * $days)))->format('Y-m-d'));
        } else {
            return new Date($this->add(new \DateInterval(sprintf('P%sD', $days)))->format('Y-m-d'));
        }
    }
}
