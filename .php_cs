# for php-cs-fixer
<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
	->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');
	
$config = Symfony\CS\Config\Config::create();
$config->fixers(
		array(
			'indentation',
			'linefeed',
			'trailing_spaces',
			'unused_use',
			'phpdoc_params',
			'short_tag',
			'return',
			'visibility',
			'php_closing_tag',
			'braces',
			'extra_empty_lines',
			'function_declaration',
			'psr0',
			'elseif',
			'eof_ending',
		)
	);
$config->finder($finder);

return $config;
