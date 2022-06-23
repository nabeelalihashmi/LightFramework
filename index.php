<?php
$start_time = microtime(true);
define('APP_DIR', dirname(__FILE__));

use IconicCodes\LightFramework\LightFramework;
include "vendor/autoload.php";
$light = new LightFramework('config', 'autoload', '__');


echo '<h1> With Defer  </h1>';
$light->defer(2, function() use ($start_time) {
    echo '1 Called at at:' . microtime(true) . '<br>'; 
});

$light->defer(1, function() use ($start_time) {
    echo '2 Called at at:' . microtime(true) . '<br>'; 
    echo __->getModule('demo')->doSomeThing('Hello, World!');
});

echo '0 Called at:' . microtime(true) . '<br>';

$light->init();

$light->setConfig('app.username', 'Icon');

var_dump($light->getConfig('app.key'));
var_dump($light->getConfig('app.username'));



class Demo {
    public $x;
    public $y;

    function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public function doSomeThing($message = "Hi!") {
        return '<br>' . $message . ': ' . (intval($this->x) + intval($this->y)) . '<br>';
    } 
}
