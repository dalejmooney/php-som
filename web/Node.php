<?php

class Node
{
    private $weights = [];
    private $x, $y;
    private $top, $left, $right, $bottom;

    /**
     * Node constructor.
     * @param $numWeights
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
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param array $data
     * @param $learningRate
     * @param $inf
     */
    public function adjustWeights(array $data, $learningRate, $inf)
    {
        for ($i = 0; $i < count($data); $i++) {
            $this->weights[$i] = $this->weights[$i] + ($inf * $learningRate * ($data[$i] - $this->weights[$i]));
        }
    }

    /**
     * @param $inputWeights
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