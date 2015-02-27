#!/usr/bin/php
<?php

ini_set('memory_limit','1024M');

include('inc/ArgumentParser.php');
include('inc/Reactor.php');
include('inc/compare.php');

$arguments = ArgumentParser::getArguments(array_slice($argv,1));


$best = new Reactor();
$best->width = $arguments['width'];
$best->height = $arguments['height'];
$best->length = $arguments['length'];
$best->fillMat = $arguments['type'];
$best->intialize();

$desired_size = $arguments['width'] * $arguments['length'];

$start_time = time();

$numberX = $arguments['rods'];
$numberO = $desired_size - $numberX;

$operationRun = isset($argv[7])?$operationRun=$argv[7]:0;
$startTime = time();

$totalToRun = factorial($desired_size) / (factorial($numberX) * factorial($numberO));

$nLines = $arguments['length'] + 5;

$tops = 0;
$lastI = 0;

$runner = function ($str)
{
    global $best;
    global $arguments;
    global $nLines;
    global $operationRun;
    global $totalToRun;
    global $startTime;

    $operationRun++;

    $reactor = new Reactor();
    $reactor->width = $arguments['width'];
    $reactor->height = $arguments['height'];
    $reactor->length = $arguments['length'];
    $reactor->fillMat = $arguments['type'];
    $reactor->currentReactorLayout = $str;
    if (compareReactorsResults($reactor, $best))
    {
        $best = $reactor;
    }

    if ($operationRun % 100 == 0)
    {
        $rResult = $reactor->getResult();
        $bResult = $best->getResult();


        $rLayout = explode("\n", $reactor->getLayoutPretty());
        $bLayout = explode("\n", $best->getLayoutPretty());


        fwrite(STDOUT, "\033[" . $nLines . "A");

        fwrite(STDOUT, "\033[2K");
        echo str_pad("Power     :  " . round($bResult['power']),40,' ', STR_PAD_RIGHT);
        echo "Power     :  " . round($rResult['power']);
        echo PHP_EOL;

        fwrite(STDOUT, "\033[2K");
        echo str_pad("Efficiency:  " . round($bResult['efficiency']),40,' ', STR_PAD_RIGHT);
        echo "Efficiency:  " . round($rResult['efficiency']);
        echo PHP_EOL;

        $lines = count($rLayout);
        for ($i =0; $i < $lines; $i++)
        {
            fwrite(STDOUT, "\033[2K");
            echo str_pad($bLayout[$i], 40, ' ', STR_PAD_RIGHT);
            echo $rLayout[$i];
            echo PHP_EOL;
        }


        $time = time() - $startTime;
        if ($time == 0) $time = 1;

        $amountLeft = $totalToRun - $operationRun;

        $timeLeft = ($time / $operationRun) * $amountLeft;

        fwrite(STDOUT, "\033[2K");
        echo $operationRun . " of " . $totalToRun . "\t" . floor($operationRun / $time) . " t/s". PHP_EOL;
        fwrite(STDOUT, "\033[2K");
        echo 'Time Remianing: ' . secs_to_h($timeLeft).PHP_EOL;
    }


    unset($reactor);
};

echo str_repeat(PHP_EOL, $nLines);

perm($numberX, $numberO, 'X', $arguments['type'],$runner, $arguments['layout']);

$bResult = $best->getResult();
echo "===BEST===" . PHP_EOL;
echo str_pad("Power     :  " . round($bResult['power']),40,' ', STR_PAD_RIGHT).PHP_EOL;
echo str_pad("Efficiency:  " . round($bResult['efficiency']),40,' ', STR_PAD_RIGHT).PHP_EOL;
echo $best->getLayoutPretty();

