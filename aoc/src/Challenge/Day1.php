<?php

namespace Aoc\Challenge;

use Aoc\Model\Elf;
use Illuminate\Support\Collection;

class Day1
{
    private Collection $elves;

    public function __construct()
    {
        $this->elves = collect([]);
    }

    public function addElf(Elf $elf): void
    {
        $this->elves->add($elf);
    }

    public function getHighestCalories(): int
    {
        return $this->sumCaloriesForTop(1);
    }

    public function sumCaloriesForTop(int $number): int
    {
        return $this->elves
            ->sortBy(fn($e) => $e->getTotalCaloriesCarried())
            ->reverse()
            ->take($number)
            ->map(fn($e) => $e->getTotalCaloriesCarried())
            ->sum();
    }
}
