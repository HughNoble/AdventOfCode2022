<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;

class Day11 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day11';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monkey business';

    private FilesystemManager $filesystemManager;

    public function __construct(FilesystemManager $filesystemManager)
    {
        parent::__construct();
        $this->filesystemManager = $filesystemManager;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $contents = $this->filesystemManager->disk('local')
            ->get('input/day11/input');
        
        $monkeys = collect(explode("\n\n", $contents))
            ->map(fn($item) => $this->stringToMonkey($item));

        $debug = false;
        
        foreach (range(1, 20) as $round) {
            foreach ($monkeys as $key => $monkey) {
                while ($baseWorryLevel = $monkey["items"]->shift()) {
                    $inspectionWorryLevel = $this->calculateWorryLevel(
                        $monkey["operation"],
                        $baseWorryLevel
                    );

                    $finalWorryLevel = (int) floor($inspectionWorryLevel / 3);

                    $destination = $finalWorryLevel % $monkey["testDivisibleBy"] === 0
                        ? $monkey["trueDestination"]
                        : $monkey["falseDestination"];
                    
                    $monkeys[$destination]["items"]->add($finalWorryLevel);

                    $monkey["inspectedItems"]++;

                    if ($debug) {
                        $this->info("Monkey: " . $key);
                        $this->info("Inspecting item with worry level: " . $baseWorryLevel);
                        $this->info("Worry info whilst inspecting: " . $inspectionWorryLevel);
                        $this->info("Worry level after inspection: " . $finalWorryLevel);
                        $this->info("Throwing to monkey: " . $destination);
                        $this->info(" ");
                    }

                    $monkeys[$key] = $monkey;
                }
            }
        }

        $ordered = $monkeys->sortBy("inspectedItems");

        $monkeyBusiness = $ordered->pop()["inspectedItems"] * $ordered->pop()["inspectedItems"];

        $this->info("Part 1: " . $monkeyBusiness);

        return Command::SUCCESS;
    }

    private function calculateWorryLevel(array $operation, int $baseWorryLevel): int
    {
        $comparisonLevel = $operation[2] === "old" ? $baseWorryLevel : (int) $operation[2];

        $worryLevel = $baseWorryLevel;

        switch ($operation[1]) {
            case "*":
                $worryLevel = $worryLevel * $comparisonLevel;
                break;
            case "+":
                $worryLevel = $worryLevel + $comparisonLevel;
                break;
        }

        return $worryLevel;
    }

    private function stringToMonkey(string $monkeyString): array
    {
        $monkeyParts = explode("\n", $monkeyString);

        $testDivisibleParts = null;
        preg_match("/divisible by (\d+)/", $monkeyParts[3], $testDivisibleParts);

        $throwPattern = "/throw to monkey (\d+)/";

        $trueDestinationParts = null;
        preg_match($throwPattern, $monkeyParts[4], $trueDestinationParts);

        $falseDestinationParts = null;
        preg_match($throwPattern, $monkeyParts[5], $falseDestinationParts);

        return [
            "items" => collect(
                explode(",", explode(":", $monkeyParts[1])[1])
            )->map(fn($item) => intval($item)),
            "operation" => explode(" ", explode(" = ", explode(":", $monkeyParts[2])[1])[1]),
            "testDivisibleBy" => (int) $testDivisibleParts[1],
            "trueDestination" => (int) $trueDestinationParts[1],
            "falseDestination" => (int) $falseDestinationParts[1],
            "inspectedItems" => 0,
        ];
    }
}
