<?php

class ArgumentParser
{
    public static function getArguments($raw)
    {
        $obj = new self($raw);

        return $obj->buildReturn();
    }

    private $raw = array();

    public function __construct($raw)
    {
        if ( ! is_array($raw))
        {
            throw new Exception('Input is bad.');
        }

        $this->validateArguments($raw);

        $this->raw = $raw;
    }

    private function validateArguments($raw)
    {
        if ( count($raw) < 5)
        {
            throw new Exception('Missing required arguments');
        }

        if ( ! is_numeric($raw[0]) || ! is_numeric($raw[1]) || ! is_numeric($raw[2]) || !is_numeric($raw[4]))
        {
            throw new Exception('Length, Width, or Height is not a number');
        }

        if ( ! in_array($raw[3], array('C', 'E', 'D', 'G', 'R', 'L', 'O')))
        {
            throw new Exception('Invalid fill type');
        }
    }

    public function buildReturn()
    {
        return array(
            'length' => $this->raw[0],
            'width' => $this->raw[1],
            'height' => $this->raw[2],
            'type' => $this->raw[3],
            'rods' => $this->raw[4],
        );
    }
}