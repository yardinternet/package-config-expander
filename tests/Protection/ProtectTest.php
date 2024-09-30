<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Tests;

use Yard\ConfigExpander\Protection\Protect;

beforeEach(function () {
    $_SERVER = [];
});

test('handleSite authorizes access', function () {
    $protect = $this->getMockBuilder(Protect::class)
        ->onlyMethods(['authorizeAccess'])
        ->getMock();

    $protect->expects($this->once())
        ->method('authorizeAccess')
        ->with('site');

    $protect->handleSite();
});

test('authorizeAccess denies access when checkIfVisitorHasAccess returns false', function () {
    $protect = $this->getMockBuilder(Protect::class)
        ->onlyMethods(['checkIfVisitorHasAccess', 'denyAccess'])
        ->getMock();

    $protect->expects($this->once())
        ->method('checkIfVisitorHasAccess')
        ->with('site')
        ->willReturn(false);

    $protect->expects($this->once())
        ->method('denyAccess');

    invokeProtectedMethod($protect, 'authorizeAccess', ['site']);
});

test('authorizeAccess grants access when checkIfVisitorHasAccess returns true', function () {
    $protect = $this->getMockBuilder(Protect::class)
        ->onlyMethods(['checkIfVisitorHasAccess', 'denyAccess'])
        ->getMock();

    $protect->expects($this->once())
        ->method('checkIfVisitorHasAccess')
        ->with('site')
        ->willReturn(true);

    $protect->expects($this->never())
        ->method('denyAccess');

    invokeProtectedMethod($protect, 'authorizeAccess', ['site']);
});

function invokeProtectedMethod($object, string $methodName, array $parameters = [])
{
    $reflection = new \ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);

    return $method->invokeArgs($object, $parameters);
}
