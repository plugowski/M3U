<?php
namespace plugowski\m3u;

use Countable;
use RecursiveIterator;

/**
 * Class M3UIterator
 * @package plugowski\m3u
 */
abstract class M3UIterator implements RecursiveIterator, Countable
{
    /**
     * @var int
     */
    protected $index    = 0;
    /**
     * @var M3UEntity[]
     */
    protected $records = array();

    /**
     * @return M3UEntity
     */
    public function current()
    {
        return $this->records[$this->index];
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->records[$this->index]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        if ($this->valid() && ($this->current() instanceof RecursiveIterator)) {
            return true;
        }

        return false;
    }

    /**
     * @return M3UEntity
     */
    public function getChildren()
    {
        return $this->records[$this->index];
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->records);
    }
}