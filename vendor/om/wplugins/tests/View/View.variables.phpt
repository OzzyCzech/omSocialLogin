<?php
require __DIR__ . '/../bootstrap.php';

\om\View::$dir = __DIR__ . '/';

try {
	$view = \om\View::from('example3');
	Assert::false(isset($view->var));

	$view->var = 'example3';
	Assert::true(isset($view->var));

	// check content
	Assert::equal('<a href="example3">link</a>', strval($view));

} catch (\om\Exception $e) {
	Assert::fail('Ups we don\'t expect same Exception');
}

// try get not existing variable
try {
	$view = \om\View::from('example3');
	$var = $view->notExistsVariable;
	Assert::fail('Try getting not existing varibale success');
} catch (\om\Exception $e) {
	Assert::true(true);
}