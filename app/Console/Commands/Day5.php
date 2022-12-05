<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;

class Day5 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day5';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Day 5 bitchez';

    private FilesystemManager $filesystemManager;
    private Collection $stacks;

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
            ->get('input/day5/input');
        
        list($stackLines, $procedureLines) = $this->separateInput($contents);

        $this->stacks = $this->stringArrayToStack($stackLines);
        collect($procedureLines)
            ->map(fn($line) => $this->procedureLineToArray($line))
            ->map(fn($line) => $this->manipulateStacks($line))
            ->last();
        
        $this->stacks
            ->map(fn($line) => $this->renderStackLine($line))
            ->each(fn($line) => $this->info($line));
        
        return Command::SUCCESS;
    }

    private function separateInput(string $input): array
    {
        $output = [];

        $i = 0;

        foreach (explode("\n", $input) as $line) {
            if ($line === "") {
                $i ++;
                continue;
            }
            
            if (!isset($output[$i])) {
                $output[$i] = [];
            }

            $output[$i][] = $line;
        }

        return $output;
    }

    private function stringArrayToStack(array $array): Collection
    {
        return collect($array)
            ->take(count($array) - 1)
            ->map(fn($line) => str_replace(["[", "] ", "]"], "", $line))
            ->map(fn($line) => str_replace("    ", " ", $line))
            ->map(fn($line) => str_split($line))
            ->map(fn($line) => collect($line));
    }

    private function procedureLineToArray(string $line): array
    {
        $pattern = '/move (\d+) from (\d+) to (\d+)/';
        $matches = [];
        preg_match($pattern, $line, $matches);

        return [
            $matches[1],
            $matches[2],
            $matches[3],
        ];
    }

    private function manipulateStacks(array $procedure): void
    {
        list($move, $from, $to) = $procedure;
        $stacks = clone($this->stacks);

        for ($i = 0; $i < $move; $i++) {
            $itemInFlight = " ";
            foreach ($stacks as $key => $line) {
                if ($line->get($from - 1) !== " ") {
                    $itemInFlight = $line->get($from - 1);
                    $stacks[$key] = $line->replace([$from - 1 => " "]);
                    break;
                }
            }
            
            $firstLine = $stacks->first();
            
            // Special case for when a new line is required
            if ($firstLine->get($to - 1) !== " ") {
                $newLine = [];

                foreach (range(1, $firstLine->count()) as $num) {
                    $newLine[] = $num == $to ? $itemInFlight : " ";
                }

                $stacks->prepend(collect($newLine));
            } else {
                foreach ($stacks as $key => $stackLine) {
                    $nextStackLine = isset($stacks[(int) $key + 1]) ? $stacks[(int) $key + 1] : null;
                    if (!$nextStackLine || $nextStackLine->get($to - 1) !== " ") {
                        $stacks[$key] = $stackLine->replace([$to - 1 => $itemInFlight]);
                        break;
                    }
                }
            }
        }

        $this->stacks = $stacks;
    }

    private function renderStackLine(Collection $line) {
        $string = "";
        foreach ($line as $item) {
            $string .= " [" . $item . "] ";
        }
        return $string;
    }

    private function ddStacks(Collection $stacks) {
        $stacks->map(fn($line) => $this->renderStackLine($line))
            ->each(fn($line) => $this->info($line));
        die();
    }
}
