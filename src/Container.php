<?php
namespace ykey\container;

use Psr\Container\ContainerInterface;
use ykey\container\entry\Lazy;
use ykey\container\entry\Raw;
use ykey\container\exception\ContainerException;
use ykey\container\exception\NotFoundException;

/**
 * Class Container
 *
 * @package ykey\container
 */
class Container implements ContainerInterface
{
    /**
     * @var EntryInterface[]
     */
    private $entries = [];
    /**
     * @var ServiceProviderInterface[]
     */
    private $providers = [];

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws ContainerException
     * @throws NotFoundException
     */
    public function get($name)
    {
        try {
            if (!isset($this->entries[$name])) {
                foreach ($this->providers as $provider) {
                    if (in_array($name, $provider->getServiceNames())) {
                        $provider->register($this);
                        break;
                    }
                }
            }
            if (isset($this->entries[$name])) {
                return $this->entries[$name]->get();
            }
        } catch (\Exception $exception) {
            throw new ContainerException(null, null, $exception);
        }
        throw new NotFoundException;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name): bool
    {
        if (isset($this->entries[$name])) {
            return true;
        }
        foreach ($this->providers as $provider) {
            if (in_array($name, $provider->getServiceNames())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ServiceProviderInterface $provider
     */
    public function addProvider(ServiceProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * @param string $name
     * @param $entry
     */
    public function raw(string $name, $entry): void
    {
        $this->entries[$name] = new Raw($entry);
    }

    /**
     * @param string   $name
     * @param callable $delegate
     * @param bool     $cacheable
     */
    public function lazy(string $name, callable $delegate, bool $cacheable = true): void
    {
        $this->entries[$name] = new Lazy($delegate, $cacheable);
    }

    /**
     * @param string $className
     * @param array  $args
     * @param bool   $cacheable
     */
    public function constraint(string $className, array $args = [], $cacheable = true): void
    {
        /* @var callable $delegate */
        $delegate = new class($className, $args) {
            private $className;
            private $args;

            public function __construct(string $className, array $args)
            {
                $this->className = $className;
                $this->args = $args;
            }

            public function __invoke()
            {
                $className = $this->className;

                return new $className(...$this->args);
            }
        };
        $this->entries[$className] = new Lazy($delegate, $cacheable);
    }
}
