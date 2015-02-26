<?php

class GeneticReactors
{
    public $number = 4;
    public $children = 10;

    /** @var Reactor[] */
    public $current_reactors = array();

    private $settings = array();

    public function __construct($settings)
    {
        $this->settings = $settings;

        $this->buildInitialReactors();
    }

    public function runIteration()
    {
        for ($i = 0; $i < $this->number; $i++)
        {
            $reactor = $this->current_reactors[$i];
            $best = $reactor->makeNew()->mutate();

            for($ii = 0; $ii < $this->children; $ii++)
            {
                do
                {
                    $child = $reactor->makeNew()->mutate();
                } while($child->currentReactorLayout == $reactor->currentReactorLayout);

                if (compareReactorsResults($child, $best))
                    $best = $child;
            }

            $this->current_reactors[$i] = $best;
        }
    }

    public function getResults()
    {
        $results = array();

        for($i = 0; $i < $this->number; $i++)
        {
            $results[$i] = array(
                'layout' => $this->current_reactors[$i]->getLayoutPretty(),
                'efficiency' => $this->current_reactors[$i]->getResult()['efficiency'],
                'power' => $this->current_reactors[$i]->getResult()['power']
            );
        }

        return $results;
    }

    private function buildInitialReactors()
    {
        for ($i = 0; $i < $this->number; $i++)
        {
            $reactor = new Reactor();
            $reactor->width = $this->settings['width'];
            $reactor->height = $this->settings['height'];
            $reactor->length = $this->settings['length'];
            $reactor->fillMat = $this->settings['type'];
            $reactor->intialize();
            $this->current_reactors[$i] = $reactor;
        }
    }


}