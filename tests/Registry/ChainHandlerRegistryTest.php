<?php

declare(strict_types=1);

namespace PHPSharkTank\Anonymizer\Tests\Registry;

use PHPSharkTank\Anonymizer\Exception\RuntimeException;
use PHPSharkTank\Anonymizer\Handler\HandlerInterface;
use PHPSharkTank\Anonymizer\Registry\ChainHandlerRegistry;
use PHPSharkTank\Anonymizer\Registry\HandlerRegistryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ChainHandlerRegistryTest extends TestCase
{
    use ProphecyTrait;

    public function testUnregister()
    {
        $this->expectException(\BadMethodCallException::class);

        $handler = new ChainHandlerRegistry([]);
        $handler->unregister('Test');
    }

    public function testGet()
    {
        $name = 'foo';
        $handler = $this->prophesize(HandlerInterface::class);

        $registryOne = $this->prophesize(HandlerRegistryInterface::class);
        $registryTwo = $this->prophesize(HandlerRegistryInterface::class);

        $registryOne->get($name)->willThrow(new RuntimeException());
        $registryTwo->get($name)->willReturn($handler->reveal());

        $registry = new ChainHandlerRegistry([
            $registryOne->reveal(),
            $registryTwo->reveal(),
        ]);

        self::assertSame($handler->reveal(), $registry->get($name));
    }

    public function testRegister()
    {
        $this->expectException(\BadMethodCallException::class);

        $handler = new ChainHandlerRegistry([]);
        $handler->register($this->prophesize(HandlerInterface::class)->reveal());
    }
}
