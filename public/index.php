<?php

use PluggitApi\VentilationUnit;

require_once __DIR__.'/../config.php';

$ventilationUnit = new VentilationUnit('192.168.21.5', 'de');

error_reporting(E_ALL);

$scale = isset($_GET['scale'])?$_GET['scale']:1.0;

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Plugit STATUS</title>
        <style type="text/css">
            html {
                transform: scale(<?php echo $scale ?>);
            }
            body {
                background-color: #005284;
                color: azure;
                font-family: Arial;
            }
            .bigtext {
                font-size: 35px;
                font-weight: bold;
            }
            #scale {
                position: absolute;
                top: 5px;
                right: 5px;
            }
            #scale a {
                font-size: 35px;
                color: azure;
                text-decoration: none;
            }
            #container {
                background-image: url('background<?php  echo ($ventilationUnit->getBypassState()==255?'_bypass':''); ?>.png');
                width: 1150px;
                height: 854px;
                top: 0;
                left: 50%;
                margin-left: -577px;
                position: absolute;
            }
            .bg-cover {
                background-color: #005284;
            }
            #info {
                font-size: 0.9em;
                width: 465px;
                top: 195px;
                left: 80px;
                position: absolute;
            }
            #north-west {
                width: 90px;
                height: 50px;
                top: 448px;
                left: 448px;
                position: absolute;
            }
            #south-west {
                width: 90px;
                height: 50px;
                top: 572px;
                left: 448px;
                position: absolute;
            }
            #north-east {
                width: 90px;
                height: 50px;
                top: 448px;
                left: 887px;
                position: absolute;
            }
            #south-east {
                width: 90px;
                height: 50px;
                top: 572px;
                left: 887px;
                position: absolute;
            }
            #fan2 {
                opacity: <?php echo ($ventilationUnit->getFanSpeedLevel(false) < 2 ? '0.7' : '0').';'.PHP_EOL; ?>
                width: 153px;
                height: 54px;
                top: 797px;
                left: 541px;
                position: absolute;
            }
            #fan3 {
                opacity: <?php echo ($ventilationUnit->getFanSpeedLevel(false) < 3 ? '0.7' : '0').';'.PHP_EOL; ?>
                width: 153px;
                height: 54px;
                top: 797px;
                left: 702px;
                position: absolute;
            }
            #fan4 {
                opacity: <?php echo ($ventilationUnit->getFanSpeedLevel(false) < 4 ? '0.7' : '0').';'.PHP_EOL; ?>
                width: 153px;
                height: 54px;
                top: 797px;
                left: 864px;
                position: absolute;
            }

        </style>
    </head>
    <body>
    <div id="scale">
        <a href="<?php echo '?scale='.sprintf("%0.1f", $scale+0.1) ?>">&plus;</a>
        <a href="<?php echo '?scale='.sprintf("%0.1f", $scale-0.1) ?>">&minus;</a>
    </div>
    <div id="container">
        <div id="north-west" class="bigtext bg-cover"><?php echo $ventilationUnit->getExtractTemperature(true); ?></div>
        <div id="south-west" class="bigtext bg-cover"><?php echo $ventilationUnit->getSupplyTemperature(true); ?></div>
        <div id="north-east" class="bigtext bg-cover"><?php echo $ventilationUnit->getOutdoorTemperature(true); ?></div>
        <div id="south-east" class="bigtext bg-cover"><?php echo $ventilationUnit->getExhaustTemperature(true); ?></div>
        <div id="fan2" class="bg-cover">&nbsp;</div>
        <div id="fan3" class="bg-cover">&nbsp;</div>
        <div id="fan4" class="bg-cover">&nbsp;</div>

        <div id="info">
            <div>
                <b>Uhrzeit:</b> <?php echo $ventilationUnit->getCurrentDateTime(true); ?><br />
                <b>Modus:</b> <?php echo $ventilationUnit->getCurrentBLState(true).' ('.($ventilationUnit->getWeekProgram(true)+1).')'; ?><br />
                <b>Filterzeit:</b> <?php echo $ventilationUnit->getFilterRemainingTime(true); ?><br />
            </div>
            <div><h2 style="margin-bottom: 5px">Beipass <span style="font-size: 0.8em; font-weight: normal;">(<?php echo $ventilationUnit->getBypassState(true); ?>)</span></h2></div>
            <div style="float: left; width: 50%;">
                <b>öffnet wenn:</b><br />
                <?php
                echo '  '.$ventilationUnit->getOutdoorTemperature(true).' < ( '.$ventilationUnit->getExtractTemperature(true).' - 2 °C )  = '.round($ventilationUnit->getExtractTemperature() - 2,1).' °C'.'<br />'.PHP_EOL;
                $match = $ventilationUnit->getOutdoorTemperature() < ($ventilationUnit->getExtractTemperature() - 2);
                echo '  '.$ventilationUnit->getOutdoorTemperature(true).' > '.$ventilationUnit->getBypassTemperatureMin(true).'<br />'.PHP_EOL;
                $match = $match && ($ventilationUnit->getOutdoorTemperature()  > $ventilationUnit->getBypassTemperatureMin());
                echo '  '.$ventilationUnit->getExtractTemperature(true).' > '.$ventilationUnit->getBypassTemperatureMax(true).'<br />'.PHP_EOL;
                $match = $match && ($ventilationUnit->getExtractTemperature() > $ventilationUnit->getBypassTemperatureMax());
                echo "-> ".($match?'<b>trifft zu</b>':'trifft nicht zu').PHP_EOL;
                ?>
            </div>
            <div style="float: left; width: 50%;">
                <b>schließt wenn:</b><br />
                <?php
                echo '  '.$ventilationUnit->getOutdoorTemperature(true).' > '.$ventilationUnit->getExtractTemperature(true).'<br />'.PHP_EOL;
                $match = $ventilationUnit->getOutdoorTemperature() > $ventilationUnit->getExtractTemperature();
                echo '  '.$ventilationUnit->getOutdoorTemperature(true).' < ('.$ventilationUnit->getBypassTemperatureMin(true).' -2 °C )  = '.round($ventilationUnit->getBypassTemperatureMin() - 2,1).' °C'.'<br />'.PHP_EOL;
                $match = $match && ($ventilationUnit->getOutdoorTemperature() < ($ventilationUnit->getBypassTemperatureMin() - 2));
                echo '  '.$ventilationUnit->getExtractTemperature(true).' < ('.$ventilationUnit->getBypassTemperatureMax(true).' -1 °C )  = '.round($ventilationUnit->getBypassTemperatureMax() - 1,1).' °C'.'<br />'.PHP_EOL;
                $match = $match && ($ventilationUnit->getExtractTemperature() < ($ventilationUnit->getBypassTemperatureMax() - 1));
                echo "-> ".($match?'<b>trifft zu</b>':'trifft nicht zu').PHP_EOL;
                ?>
            </div>
        </div>
    </div>

    <div style="position: absolute; top: 1200px;"><?php
        echo "<pre>";
        echo PHP_EOL;
        echo "<h2>--- PluggitApi ---</h2>".PHP_EOL;
        echo "Current date and time: ".$ventilationUnit->getCurrentDateTime(true).' ('.$ventilationUnit->getCurrentDateTime().')'.PHP_EOL;
        echo "Start exploitation date: ".$ventilationUnit->getStartExploitationDate(true).' ('.$ventilationUnit->getStartExploitationDate().')'.PHP_EOL;
        echo "Worktime: ".$ventilationUnit->getWorkTime(true).' ('.$ventilationUnit->getWorkTime().')'.PHP_EOL;
        echo "Outdoor temperature: ".$ventilationUnit->getOutdoorTemperature(true).' ('.$ventilationUnit->getOutdoorTemperature().')'.PHP_EOL;
        echo "Supply temperature: ".$ventilationUnit->getSupplyTemperature(true).' ('.$ventilationUnit->getSupplyTemperature().')'.PHP_EOL;
        echo "Extract temperature: ".$ventilationUnit->getExtractTemperature(true).' ('.$ventilationUnit->getExtractTemperature().')'.PHP_EOL;
        echo "Exhaust temperature: ".$ventilationUnit->getExhaustTemperature(true).' ('.$ventilationUnit->getExhaustTemperature().')'.PHP_EOL;
        echo "Fan-1 speed: ".$ventilationUnit->getFanSpeed1(true).' ('.$ventilationUnit->getFanSpeed1().')'.PHP_EOL;
        echo "Fan-2 speed: ".$ventilationUnit->getFanSpeed2(true).' ('.$ventilationUnit->getFanSpeed2().')'.PHP_EOL;
        echo "<b>Fan speed level: ".$ventilationUnit->getFanSpeedLevel(true).' ('.$ventilationUnit->getFanSpeedLevel().')</b>'.PHP_EOL;
        echo "Filter default time: ".$ventilationUnit->getFilterDefaultTime(true),' ('.$ventilationUnit->getFilterDefaultTime().')'.PHP_EOL;
        echo "Filter remaining time: ".$ventilationUnit->getFilterRemainingTime(true).' ('.$ventilationUnit->getFilterRemainingTime().')'.PHP_EOL;
        echo "<b>Bypass state: ".$ventilationUnit->getBypassState(true).' ('.$ventilationUnit->getBypassState().')</b>'.PHP_EOL;
        echo "BypassTemperatureMin: ".$ventilationUnit->getBypassTemperatureMin(true).' ('.$ventilationUnit->getBypassTemperatureMin().')'.PHP_EOL;
        echo "BypassTemperatureMax: ".$ventilationUnit->getBypassTemperatureMax(true).' ('.$ventilationUnit->getBypassTemperatureMax().')'.PHP_EOL;
        echo "Bypass manual timeout: ".$ventilationUnit->getBypassManualTimeout(true).' ('.$ventilationUnit->getBypassManualTimeout().')'.PHP_EOL;
        echo "Preheater duty cycle: ".$ventilationUnit->getPreheaterDutyCycle(true).' ('.$ventilationUnit->getPreheaterDutyCycle().')'.PHP_EOL;
        echo "Unit mode: ".$ventilationUnit->getUnitMode(true).' ('.$ventilationUnit->getUnitMode().')'.PHP_EOL;
        echo "CurrentBLState: ".$ventilationUnit->getCurrentBLState(true).' ('.$ventilationUnit->getCurrentBLState().')'.PHP_EOL;
        echo "WeekProgram: ".($ventilationUnit->getWeekProgram(true)+1).' ('.$ventilationUnit->getWeekProgram(false).')'.PHP_EOL;
        echo "</pre>".PHP_EOL;
    ?></div>

    </body>
</html>
