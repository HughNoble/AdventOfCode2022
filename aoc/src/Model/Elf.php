<?php

namespace Aoc\Model;

class Elf
{
    private array $food;

    public function __construct(array $food)
    {
        $this->food = $food;
    }

    public function getTotalCaloriesCarried(): int
    {
        return array_sum($this->food);
    }
}
