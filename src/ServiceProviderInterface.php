<?php
namespace ykey\container;

/**
 * Interface ServiceProviderInterface
 *
 * @package ykey\container
 */
interface ServiceProviderInterface
{
    /**
     * @return string[]
     */
    public function getServiceNames(): array;

    /**
     * @param Container $container
     */
    public function register(Container $container): void;
}
