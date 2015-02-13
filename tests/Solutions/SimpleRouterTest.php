<?php


namespace Pinepain\SimpleRouting\Tests\Solutions;


use Pinepain\SimpleRouting\Solutions\SimpleRouter;

class SimpleRouterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Pinepain\SimpleRouting\Solutions\SimpleRouter::__construct
     * @covers \Pinepain\SimpleRouting\Solutions\SimpleRouter::add
     */
    public function testAdd()
    {
        $collector = $this->getMockBuilder('Pinepain\SimpleRouting\RoutesCollector')
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $generator = $this->getMockBuilder('Pinepain\SimpleRouting\RulesGenerator')
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcher = $this->getMockBuilder('Pinepain\SimpleRouting\Dispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $collector->expects($this->at(0))
            ->method('add')
            ->with('route-1', 'handler-1')
            ->willReturn('parsed-1');

        $collector->expects($this->at(1))
            ->method('add')
            ->with('route-2', 'handler-2')
            ->willReturn('parsed-2');

        $router = new SimpleRouter($collector, $generator, $dispatcher);

        $this->assertEquals('parsed-1', $router->add('route-1', 'handler-1'));
        $this->assertEquals('parsed-2', $router->add('route-2', 'handler-2'));
    }

    /**
     * @covers \Pinepain\SimpleRouting\Solutions\SimpleRouter::dispatch
     */
    public function testDispatch()
    {
        $collector = $this->getMockBuilder('Pinepain\SimpleRouting\RoutesCollector')
            ->setMethods(['getStaticRoutes', 'getDynamicRoutes'])
            ->disableOriginalConstructor()
            ->getMock();

        $collector->expects($this->exactly(2))
            ->method('getStaticRoutes')->with()->willReturnOnConsecutiveCalls(['static-routes-1'], ['static-routes-2']);

        $collector->expects($this->exactly(2))
            ->method('getDynamicRoutes')->with()->willReturnOnConsecutiveCalls(['dynamic-routes-1'], ['dynamic-routes-2']);

        $generator = $this->getMockBuilder('Pinepain\SimpleRouting\RulesGenerator')
            ->setMethods(['generate'])
            ->disableOriginalConstructor()
            ->getMock();

        $generator->expects($this->exactly(2))
            ->method('generate')
            ->withConsecutive([['dynamic-routes-1']], [['dynamic-routes-2']])
            ->willReturnOnConsecutiveCalls(['dynamic-generated-rules-1'], ['dynamic-generated-rules-2']);

        $dispatcher = $this->getMockBuilder('Pinepain\SimpleRouting\Dispatcher')
            ->setMethods(['setStaticRules', 'setDynamicRules', 'dispatch'])
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcher->expects($this->exactly(2))
            ->method('setStaticRules')
            ->withConsecutive([['static-routes-1']], [['static-routes-2']]);

        $dispatcher->expects($this->exactly(2))
            ->method('setDynamicRules')
            ->withConsecutive([['dynamic-generated-rules-1']], [['dynamic-generated-rules-2']]);

        $dispatcher->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(['url-1'], ['url-2'])
            ->willReturnOnConsecutiveCalls(['handler-1', 'variables-1'], ['handler-2', 'variables-2']);

        $router = new SimpleRouter($collector, $generator, $dispatcher);

        $this->assertSame(['handler-1', 'variables-1'], $router->dispatch('url-1'));
        $this->assertSame(['handler-2', 'variables-2'], $router->dispatch('url-2'));
    }

}