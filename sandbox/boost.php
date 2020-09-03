<?php

require_once __DIR__.'/../config.php';

$ventilationUnit = new \PluggitApi\VentilationUnit('192.168.21.5', 'de');

echo PHP_EOL;
echo "------- Pluggit VentilationUnit -------".PHP_EOL;
echo "Current fan level: ".$ventilationUnit->getFanSpeedLevel().PHP_EOL;
$ventilationUnit->setFanSpeedLevel(2);
echo "Set fan level to: ".$ventilationUnit->getFanSpeedLevel().PHP_EOL;
echo PHP_EOL;
echo "Current fan level: ".$ventilationUnit->getFanSpeedLevel().PHP_EOL;
$ventilationUnit->setFanSpeedLevel(4);
echo "Set fan level to: ".$ventilationUnit->getFanSpeedLevel().PHP_EOL;
echo PHP_EOL;
