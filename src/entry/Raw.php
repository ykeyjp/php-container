<?php
namespace ykey\container\entry;

use ykey\container\EntryInterface;

/**
 * Class Raw
 *
 * @package ykey\container\entry
 */
class Raw implements EntryInterface
{
    /**
     * @var mixed
     */
    private $entry;

    /**
     * Raw constructor.
     *
     * @param $entry
     */
    public function __construct($entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->entry;
    }
}
