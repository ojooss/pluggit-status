<?php

require_once __DIR__.'/../config.php';

$ventilationUnit = new \PluggitApi\VentilationUnit('192.168.21.5', 'de');

echo PHP_EOL;
echo "--- PluggitApi ---".PHP_EOL;
echo "Outdoor temperature: ".$ventilationUnit->getOutdoorTemperature(true).PHP_EOL;
echo "Supply  temperature: ".$ventilationUnit->getSupplyTemperature(true).PHP_EOL;
echo "Extract temperature: ".$ventilationUnit->getExtractTemperature(true).PHP_EOL;
echo "Exhaust temperature: ".$ventilationUnit->getExhaustTemperature(true).PHP_EOL;
echo PHP_EOL;

echo '-- Beipass --'.PHP_EOL;
echo "aktueller Status: " . $ventilationUnit->getBypassState(true) . PHP_EOL;
echo "Temperature Min: ".$ventilationUnit->getBypassTemperatureMin(true).PHP_EOL;
echo "Temperature Max: ".$ventilationUnit->getBypassTemperatureMax(true).PHP_EOL;
echo "Bypass manual timeout: ".$ventilationUnit->getBypassManualTimeout(true).PHP_EOL;
echo PHP_EOL;

echo '-- Automatic bypass --'.PHP_EOL;
echo 'The by-pass will open when all the following conditions are fulfilled:'.PHP_EOL;
echo '  '.$ventilationUnit->getOutdoorTemperature(true).' < '.$ventilationUnit->getExtractTemperature(true).' - 2  ('.round($ventilationUnit->getExtractTemperature() - 2,1).' °C)'.PHP_EOL;
$open = $ventilationUnit->getOutdoorTemperature() < ($ventilationUnit->getExtractTemperature() - 2);
echo '  '.$ventilationUnit->getOutdoorTemperature(true).' > '.$ventilationUnit->getBypassTemperatureMin(true).PHP_EOL;
$open = $open && ($ventilationUnit->getOutdoorTemperature()  > $ventilationUnit->getBypassTemperatureMin());
echo '  '.$ventilationUnit->getExtractTemperature(true).' > '.$ventilationUnit->getBypassTemperatureMax(true).PHP_EOL;
$open = $open && ($ventilationUnit->getExtractTemperature() > $ventilationUnit->getBypassTemperatureMax());
echo "-> ".($open?'trifft zu':'trifft nicht zu').PHP_EOL;
echo PHP_EOL;
echo 'And close if one of the following conditions is fulfilled while open:'.PHP_EOL;
echo '  '.$ventilationUnit->getOutdoorTemperature(true).' > '.$ventilationUnit->getExtractTemperature(true).PHP_EOL;
$open = $ventilationUnit->getOutdoorTemperature() > $ventilationUnit->getExtractTemperature();
echo '  '.$ventilationUnit->getOutdoorTemperature(true).' < '.$ventilationUnit->getBypassTemperatureMin(true).' -2  ('.round($ventilationUnit->getBypassTemperatureMin() - 2,1).' °C)'.PHP_EOL;
$open = $open && ($ventilationUnit->getOutdoorTemperature() < ($ventilationUnit->getBypassTemperatureMin() - 2));
echo '  '.$ventilationUnit->getExtractTemperature(true).' < '.$ventilationUnit->getBypassTemperatureMax(true).' -1  ('.round($ventilationUnit->getBypassTemperatureMax() - 1,1).' °C)'.PHP_EOL;
$open = $open && ($ventilationUnit->getExtractTemperature() < ($ventilationUnit->getBypassTemperatureMax() - 1));
echo "-> ".($open?'trifft zu':'trifft nicht zu').PHP_EOL;
echo PHP_EOL;
