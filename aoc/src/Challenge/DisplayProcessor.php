<?php

namespace Aoc\Challenge;

class DisplayProcessor
{
    public function processInstructions(array $instructions): array
    {
        $x = 1;
        $stack = [];
        $cycle = 0;

        foreach ($instructions as $instruction) {
            $cycle++;
            $stack[$cycle] = $x;

            if ($instruction == "noop") {
                continue;
            }

            list($operation, $value) = explode(" ", $instruction);

            $cycle++;
            $stack[$cycle] = $x;

            $x += $value;
        }

        return $stack;
    }
}
