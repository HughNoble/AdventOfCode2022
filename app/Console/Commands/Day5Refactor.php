<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;

class Day5Refactor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day5-2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Day 5 but good';

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

        $this->stacks = $this->stringArrayToStacks($stackLines);
        collect($procedureLines)
            ->map(fn ($line) => $this->procedureLineToArray($line))
            ->map(fn ($line) => $this->manipulateStacks($line))
            ->last();
        
        $this->info($this->renderStacks());

        return Command::SUCCESS;
    }

    private function separateInput(string $input): array
    {
        $output = [];

        $i = 0;

        foreach (explode("\n", $input) as $line) {
            if ($line === "") {
                $i++;
                continue;
            }

            if (!isset($output[$i])) {
                $output[$i] = [];
            }

            $output[$i][] = $line;
        }

        return $output;
    }

    private function stringArrayToStacks(array $array): Collection
    {
        $stackLines = collect($array)
            ->take(count($array) - 1)
            ->map(fn ($line) => str_replace(["[", "] ", "]"], "", $line))
            ->map(fn ($line) => str_replace("    ", " ", $line))
            ->map(fn ($line) => str_split($line));

        $stacks = [];
        
        foreach ($stackLines as $line) {
            foreach ($line as $key => $value) {
                if (!isset($stacks[$key + 1])) {
                    $stacks[$key + 1] = new Collection();
                }
                if ($value != " ") {
                    $stacks[$key + 1][] = $value;
                }
            }
        }
        
        return collect($stacks)->map(fn ($stack) => $stack->reverse()->flatten());
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

        $itemsInFlight = [];
        for ($i = 0; $i < $move; $i++) {
            $itemsInFlight[] = $this->stacks[$from]->pop();
        }

        $itemsInFlight = array_reverse($itemsInFlight);

        foreach ($itemsInFlight as $itemInFlight) {
            $this->stacks[$to][] = $itemInFlight;
        }
    }

    private function renderStacks()
    {
        $string = "";
        foreach ($this->stacks as $stack) {
            $string .= $stack->last();
        }
        return $string;
    }
}
