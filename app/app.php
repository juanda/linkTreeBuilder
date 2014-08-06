<?php

include __DIR__ . "/../vendor/autoload.php";

$linkTreeBuilderCommand = new \Jazzyweb\LinkTreeBuilder\Command\LinkTreeBuilderCommand();

$app = new \Symfony\Component\Console\Application();

$app->add($linkTreeBuilderCommand);

$app->run();