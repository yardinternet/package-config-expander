<?php

declare(strict_types=1);

namespace Yard\ConfigExpander\Tests;

use Yard\ConfigExpander\Protection\Protect;
use Yard\ConfigExpander\Protection\WhitelistEntity;

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

test('handleLogin does not authorize access if not login page', function () {
    $protect = $this->getMockBuilder(Protect::class)
                    ->onlyMethods(['isLoginPage', 'authorizeAccess'])
                    ->getMock();

    $protect->expects($this->once())
            ->method('isLoginPage')
            ->willReturn(false);

    $protect->expects($this->never())
            ->method('authorizeAccess');

    $protect->handleLogin();
});

test('handleLogin authorizes access if login page', function () {
    $protect = $this->getMockBuilder(Protect::class)
                    ->onlyMethods(['isLoginPage', 'authorizeAccess'])
                    ->getMock();

    $protect->expects($this->once())
            ->method('isLoginPage')
            ->willReturn(true);

    $protect->expects($this->once())
            ->method('authorizeAccess')
            ->with('login');

    $protect->handleLogin();
});

test('isLoginPage returns true for wp-login.php', function () {
    $_SERVER['PHP_SELF'] = '/wp-login.php';
    $protect = new Protect();
    $result = invokeProtectedMethod($protect, 'isLoginPage');
    expect($result)->toBeTrue();
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
