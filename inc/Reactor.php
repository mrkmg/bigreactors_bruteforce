<?php

class Reactor
{
    public $width;
    public $length;
    public $height;

    public $fillMat;

    public $currentReactorLayout;

    private $resultsCurrent = false;
    private $lastResult = array();


    public function intialize()
    {
        $this->createDefaultLayout();
        $this->currentReactorLayout[0] = 'X';
    }

    public function createDefaultLayout()
    {
        $str_length = $this->width * $this->length;

        $this->currentReactorLayout = str_repeat($this->fillMat, $str_length);
    }

    public function replaceBlockAt($spot, $block)
    {
        $this->currentReactorLayout = substr_replace($this->currentReactorLayout, $block, $spot, 1);
        $this->resultsCurrent = false;
    }

    public function getBlockAt($spot)
    {
        return substr($this->currentReactorLayout, $spot, 1);
        $this->resultsCurrent = false;
    }

    public function getLayoutPretty()
    {
        return chunk_split($this->currentReactorLayout, $this->width, PHP_EOL);
    }

    public function getResult()
    {
        if ($this->resultsCurrent) return $this->lastResult;
        $request = array(
            'xSize' => $this->width + 2,
            'zSize' => $this->length + 2,
            'height' => $this->height + 2,
            'layout' => $this->currentReactorLayout,
            'isActivelyCooled' => false,
            'controlRodInsertion' => 0
        );

        $query = urlencode(json_encode($request));

        $rawResult = file_get_contents('http://127.0.0.1:8081/api/simulate?definition=' . $query);

        if (strlen($rawResult) == 0) throw new Exception("Failed to run simulation");

        $result = json_decode($rawResult, true);

        $output = $result['output'];
        $usage = $result['fuelConsumption'];

        $efficiency = $output / $usage;

        $this->lastResult = array('power' => $output, 'efficiency' => $efficiency);
        $this->resultsCurrent = true;

        return $this->lastResult;
    }

    public function makeNew()
    {
        $new = new self;
        $new->width = $this->width;
        $new->length = $this->length;
        $new->height = $this->height;
        $new->fillMat = $this->fillMat;
        $new->currentReactorLayout = $this->currentReactorLayout;

        return $new;
    }

}