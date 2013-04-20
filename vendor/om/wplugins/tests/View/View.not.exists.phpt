<?php
require __DIR__ . '/../bootstrap.php';

$view = \om\View::$dir = __DIR__ . '/';

try {
	$view = \om\View::from('no exists file');
	\Tester\Assert::fail('File not exists Exception expected.');
} catch (\om\Exception $e) {
	\Tester\Assert::true(true);
}
