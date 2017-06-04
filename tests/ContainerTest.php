<?php
namespace ykey\container;

use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testRaw()
    {
        $container = new Container();
        $container->raw('test', 'value');
        $this->assertTrue($container->has('test'));
        $this->assertEquals('value', $container->get('test'));
    }

    public function testLazy()
    {
        $container = new Container();
        $container->lazy('test', function () {
            return 'value';
        });
        $this->assertTrue($container->has('test'));
        $this->assertEquals('value', $container->get('test'));
    }

    public function testConstraint()
    {
        $container = new Container();
        $container->constraint(ServiceExample::class, ['value']);
        $this->assertTrue($container->has(ServiceExample::class));
        $this->assertAttributeEquals('value', 'test', $container->get(ServiceExample::class));
    }

    public function testProvider()
    {
        $container = new Container();
        $container->addProvider(new ProviderExample);
        $this->assertTrue($container->has('test'));
        $this->assertEquals('value', $container->get('test'));
    }
}

class ProviderExample implements ServiceProviderInterface
{
    public function getServiceNames(): array
    {
        return ['test'];
    }

    public function register(Container $container): void
    {
        $container->raw('test', 'value');
    }
}

class ServiceExample
{
    public $test;

    public function __construct($test)
    {
        $this->test = $test;
    }
}
