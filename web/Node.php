<?php

class Node
{
    private $weights = [];
    private $x, $y;
    private $top, $left, $right, $bottom;

    /**
     * Node constructor.
     * @param int $top Top node position
     * @param int $left Left node position
     * @param int $right right node position
     * @param int $bottom bottom node position
     * @param int $numWeights Total number of weights to use.
     */
    public function __construct($top, $left, $right, $bottom, $numWeights)
    {
        $this->top = $top;
        $this->left = $left;
        $this->right = $right;
        $this->bottom = $bottom;

        for ($i = 0; $i < $numWeights; $i++) {
            $this->weights[$i] = mt_rand(0, 255);
        }

        $this->x = $this->left + (double)($this->right - $this->left) / 2;
        $this->y = $this->top + (double)($this->bottom - $this->top) / 2;
    }

    /**
     * Get the X cords of the node within grid.
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Get the Y cords of node within grid.
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Adjust the weights of this node based on learning rate and inf.
     * @param array $data
     * @param float $learningRate
     * @param float $inf
     */
    public function adjustWeights(array $data, $learningRate, $inf)
    {
        for ($i = 0; $i < count($data); $i++) {
            $this->weights[$i] = $this->weights[$i] + ($inf * $learningRate * ($data[$i] - $this->weights[$i]));
        }
    }

    /**
     * Get distance between this node and input weights.
     * @param array $inputWeights
     * @return float
     */
    public function getDistance($inputWeights)
    {
        $distance = 0;

        for ($i = 0; $i < $max = count($this->weights); $i++) {
            $distance += ($inputWeights[$i] - $this->weights[$i]) * ($inputWeights[$i] - $this->weights[$i]);
        }

        return sqrt($distance);
    }

    /**
     * Get weights as a comma separated string.
     * @return string
     */
    public function getWeightAsString()
    {
        $data = $this->weights;
        for ($i = 0; $i < count($this->weights); $i++) {
            $data[$i] = round($data[$i]);
        }

        return implode(",", $data);
    }
}