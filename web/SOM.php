<?php

class SOM
{
    private $iterations = 100;
    /** @var int */
    private $iteration = 100;
    /** @var int */
    private $iterationCount = 0;
    /** @var int */
    private $width, $height;
    /** @var array */
    private $grid = [];
    /** @var float|int */
    private $mapRadius;
    /** @var  float */
    private $timeConstant;
    /** @var  float */
    private $neighbourhoodRadius;
    /** @var  float */
    private $learningRate;

    /**
     * The base learning rate.
     */
    const LEARNING_RATE = 0.1;


    /**
     * SOM constructor.
     * @param int $iterations Number of iterations to train for.
     * @param int $width Width of grid.
     * @param int $height Height of grid.
     */
    public function __construct($iterations, $width, $height)
    {
        $this->iterations = $iterations;
        $this->iteration = $iterations;
        $this->width = $width;
        $this->height = $height;
        $this->mapRadius = max($width, $height) / 2;
        $this->timeConstant = $iterations / log($this->mapRadius);

        $this->initalizeGrid();
    }

    /**
     * Train the SOM based on sample data.
     * @param array $data
     */
    public function epoch(array $data)
    {
        while ($this->iteration > 0) {

            $this->learningRate = self::LEARNING_RATE * exp(-(double)$this->iterationCount / $this->iterations);

            $selectedData = $data[mt_rand(0, count($data) - 1)];
            $winningNode = $this->findBestMatchingUnit($selectedData);
            $this->neighbourhoodRadius = $this->mapRadius * exp(-(double)$this->iterationCount / $this->timeConstant);

            for ($i = 0; $i < $this->height; $i++) {
                for ($j = 0; $j < $this->width; $j++) {
                    $distanceToNode = (($winningNode->getX() - $j) * ($winningNode->getX() - $j) + ($winningNode->getY() - $i) * ($winningNode->getY() - $i));
                    $widthSq = $this->neighbourhoodRadius * $this->neighbourhoodRadius;

                    if ($distanceToNode < ($widthSq)) {
                        $inf = exp(-($distanceToNode) / (2 * $widthSq));
                        $this->grid[$i][$j]->adjustWeights($selectedData, $this->learningRate, $inf);
                    }
                }
            }

            $this->learningRate = self::LEARNING_RATE * exp(-(double)$this->iterationCount / $this->iterations);

            $this->iteration--;
            $this->iterationCount++;

        }
    }

    /**
     * Get the best matching node to the weights passed.
     * @param array $data
     * @return Node
     */
    public function findBestMatchingUnit(array $data)
    {
        $bestDistance = 1000000;
        $bestNode = null;

        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {

                $node = $this->grid[$i][$j];
                $distance = $node->getDistance($data);

                if ($distance < $bestDistance) {
                    $bestNode = $node;
                }
                $bestDistance = min($bestDistance, $distance);
            }
        }

        return $bestNode;
    }

    /**
     * Render as a table. This will by default use colours.
     */
    public function render($find = null)
    {
        if (null !== $find) {
            $plot = $this->findBestMatchingUnit($find);
        }

        echo '<table cellpadding="0" cellspacing="0" border="0">';
        for ($i = 0; $i < $this->height; $i++) {
            echo '<tr>';
            for ($j = 0; $j < $this->width; $j++) {

                $colour = $this->grid[$i][$j]->getWeightAsString();
                $borderColour = $colour;

                if (null !== $plot && $plot->getX() == $j && $plot->getY() == $i) {
                    $borderColour = '255,255,255';
                }

                echo '<td cellspacing="0" cellpadding="0" style="border:2px solid rgb(' . $borderColour . ');0px;background-color: rgb(' . $colour . '); width:10px; height:10px;"></td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    /**
     * Intialize inital grid data.
     */
    private function initalizeGrid()
    {
        for ($i = 0; $i < $this->height; $i++) {
            $this->grid[$i] = [];
            for ($j = 0; $j < $this->width; $j++) {

                $top = ($i == 0) ? null : $i - 1;
                $left = ($j == 0) ? null : $j - 1;
                $right = ($j == $this->width - 1) ? null : $j + 1;
                $bottom = ($i == $this->height - 1) ? null : $i + 1;

                $this->grid[$i][$j] = new Node($top, $left, $right, $bottom, 3);
            }
        }
    }
}