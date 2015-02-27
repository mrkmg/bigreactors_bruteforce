#!/usr/bin/php
<?php

include('inc/compare.php');

$total = 0;

$counter = function($s){
    global $total;
    $total++;
    echo $s . PHP_EOL;
};

perm(3, 3, 'X', 'O', $counter);


echo $total . PHP_EOL;
