<?php

namespace Aoc\Challenge;

class Day3
{
    public function getCommonItems(array $items): array
    {
        $split = collect($items)->split(2);

        $compartment1 = $split->get(0);
        $compartment2 = $split->get(1);

        return $compartment1->filter(fn($item) => $compartment2->contains($item))
            ->unique()
            ->flatten()
            ->toArray();
    }

    public function scoreItem(string $item): int
    {
        $map = [];

        foreach (range("a", "z") as $key => $letter) {
            $map[$letter] = $key + 1;
        }

        foreach (range("A", "Z") as $key => $letter) {
            $map[$letter] = $key + 27;
        }

        return $map[$item];
    }

    public function scoreItems(array $items): int
    {
        return collect($items)
            ->map(fn($item) => $this->scoreItem($item))
            ->sum();
    }
}
