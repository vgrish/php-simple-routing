<?php


namespace Pinepain\SimpleRouting\Tests;


use Pinepain\SimpleRouting\CompiledRoute;

class CompiledRouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Pinepain\SimpleRouting\CompiledRoute::__construct
     * @covers \Pinepain\SimpleRouting\CompiledRoute::getRegex
     * @covers \Pinepain\SimpleRouting\CompiledRoute::getVariables
     * @covers \Pinepain\SimpleRouting\CompiledRoute::hasOptional
     */
    public function testConstructorAndGetters()
    {
        $route = new CompiledRoute('regex', ['variable' => 'default'], 'bool in real life');

        $this->assertEquals('regex', $route->getRegex());
        $this->assertEquals(['variable' => 'default'], $route->getVariables());
        $this->assertEquals('bool in real life', $route->hasOptional());
    }
}
