<?php

return [

    // identifer => Classname or Callable, arguments, callback
    'demo' => [Demo::class, [10, 12], function($demo) {
    }, false],

    'hello' => [function($x) {
        echo 'Hello : ' . $x;
    }, function($hello) {
        echo 'Loaded Hello';
    }]
];
