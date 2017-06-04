<?php
namespace ykey\container\entry;

use ykey\container\EntryInterface;

/**
 * Class Lazy
 *
 * @package ykey\container\entry
 */
class Lazy implements EntryInterface
{
    /**
     * @var mixed
     */
    private $entry;
    /**
     * @var callable
     */
    private $delegate;
    /**
     * @var bool
     */
    private $cacheable;

    /**
     * Lazy constructor.
     *
     * @param callable $delegate
     * @param bool     $cacheable
     */
    public function __construct(callable $delegate, bool $cacheable = true)
    {
        $this->delegate = $delegate;
        $this->cacheable = $cacheable;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if (!is_null($this->entry)) {
            return $this->entry;
        }
        $entry = ($this->delegate)();
        if ($this->cacheable) {
            $this->entry = $entry;
        }

        return $entry;
    }
}
