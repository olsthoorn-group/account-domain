# for php-cs-fixer
<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
	->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$config = Symfony\CS\Config\Config::create();
$config->fixers(
		array(
		    'psr0',
			'psr1',
			'psr2'
		)
	);
$config->finder($finder);

return $config;
