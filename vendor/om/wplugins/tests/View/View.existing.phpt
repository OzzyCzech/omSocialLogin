<?php
require __DIR__ . '/../bootstrap.php';

\om\View::$dir = __DIR__ . '/';

try {
	$view = \om\View::from('example');
	Assert::same('example.phtml', $view->getTemplate());

	$view = \om\View::from('example2');
	Assert::same('example2.php', $view->getTemplate());

	$view = \om\View::from(__DIR__ . '/example');
	Assert::same(__DIR__ . '/example.phtml', $view->getTemplate());

	$view = \om\View::from(__DIR__ . '/example2');
	Assert::same(__DIR__ . '/example2.php', $view->getTemplate());

} catch (\om\Exception $e) {
	\Tester\Assert::fail('File example.phtml and example2.php exists but View throw exception.');
}

// check example 1 content
$view = \om\View::from('example');
Assert::equal('<a href="example">link</a>', strval($view));

// check example 2 content
$view2 = \om\View::from('example2');
Assert::equal('<a href="example2">link</a>', strval($view2));