<?php

require_once __DIR__.'/../config.php';

PluggitApi\Translation::singleton('de');

$ipAddress = '192.168.21.5';
$modbus = new \PHPModbus\ModbusMasterTcp($ipAddress);

echo PHP_EOL;
echo "--- TEST ---".PHP_EOL;
echo PHP_EOL;

$address = 40445;

class TestRegister extends \PluggitApi\Register\Floating
{
    public function writeValue($value)
    {
        $this->modbus->writeMultipleRegister(0, $this->reference, [$value, 0], ["INT", "INT"]);
    }
}

$register = new TestRegister(
    $modbus,
    ($address - 40001),
    $address,
    '',
    '');
echo "Regsiter address: ".$address.PHP_EOL.
     "Actual value: " . $register->getValue(true) . ' ('. $register->getValue().')' . PHP_EOL;


$value = 16;
echo "Try to set value to: " . $value . PHP_EOL;
$register->writeValue($value);

echo "New value: " . $register->getValue(true) . ' ('. $register->getValue().')' . PHP_EOL;


echo PHP_EOL;
