<?php
namespace FleurMemoire\Util;

class Term implements \IteratorAggregate
{
    /**
     * @var Date
     */
    private $start;

    /**
     * @var Date
     */
    private $end;

    public function __construct(Date $start, Date $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return Date
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return Date
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->iterator();
    }

    /**
     * @return \Generator
     */
    private function iterator()
    {
        $current = clone $this->start;
        while (true)
        {
            yield $current;
            $current = $current->addDays(1);
            if ($current > $this->end) {
                break;
            }
        }
    }
}
