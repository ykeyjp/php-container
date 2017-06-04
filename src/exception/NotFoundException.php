<?php
namespace ykey\container\exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class NotFoundException
 *
 * @package ykey\container\exception
 */
class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
}
