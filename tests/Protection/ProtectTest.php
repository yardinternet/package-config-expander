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

test('authorizeAccess skips the maintenance page for a whitelisted frontend visitor', function (string $entityType, bool $skipsMaintenance) {
	$protect = $this->getMockBuilder(Protect::class)
		->onlyMethods([
			'maintenanceModeEnabled',
			'shouldShowMaintenancePage',
			'getWhitelistedEntities',
			'ipCurrentVisitor',
			'getMaintenancePage',
			'setNoCacheHeader',
			'checkIfVisitorHasAccess',
		])
		->getMock();

	$protect->method('maintenanceModeEnabled')->willReturn(true);
	$protect->method('shouldShowMaintenancePage')->willReturn(true);
	$protect->method('checkIfVisitorHasAccess')->willReturn(true);
	$protect->method('ipCurrentVisitor')->willReturn('1.2.3.4');
	$protect->method('getWhitelistedEntities')->willReturn([
		new WhitelistEntity(['whitelisted_ip_address' => '1.2.3.4', 'type' => $entityType]),
	]);

	$protect->expects($skipsMaintenance ? $this->never() : $this->once())
		->method('getMaintenancePage');

	invokeProtectedMethod($protect, 'authorizeAccess', ['site']);
})->with([
	'frontend entity' => ['site', true],
	'both entity' => ['both', true],
	'admin only entity' => ['login', false],
]);

test('authorizeAccess shows the maintenance page for a visitor who is not whitelisted', function () {
	$protect = $this->getMockBuilder(Protect::class)
		->onlyMethods([
			'maintenanceModeEnabled',
			'shouldShowMaintenancePage',
			'getWhitelistedEntities',
			'ipCurrentVisitor',
			'getMaintenancePage',
			'setNoCacheHeader',
			'checkIfVisitorHasAccess',
		])
		->getMock();

	$protect->method('maintenanceModeEnabled')->willReturn(true);
	$protect->method('shouldShowMaintenancePage')->willReturn(true);
	$protect->method('checkIfVisitorHasAccess')->willReturn(true);
	$protect->method('ipCurrentVisitor')->willReturn('9.9.9.9');
	$protect->method('getWhitelistedEntities')->willReturn([
		new WhitelistEntity(['whitelisted_ip_address' => '1.2.3.4', 'type' => 'both']),
	]);

	$protect->expects($this->once())->method('getMaintenancePage');

	invokeProtectedMethod($protect, 'authorizeAccess', ['site']);
});
