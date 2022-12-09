<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;

class Day9 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day9';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private FilesystemManager $filesystemManager;

    private bool $debug = false;

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
            ->get('input/day9/input');
        
        $lines = explode("\n", $contents);

        $this->debug = false;

        $positions = $this->parsePositions($lines);
        $part1 = $this->getTailPositions($positions, 1);

        $this->info($part1->count());

        $part2 = $this->getTailPositions($positions, 9);

        $this->info($part2->count());

        return Command::SUCCESS;
    }

    private function getTailPositions(Collection $positions, int $length): Collection
    {
        foreach (range(1, $length) as $i) {
            $lastPosition = [0, 0];

            $positions = $positions->map(function($item) use (&$lastPosition) {
                $currentTailPosition = $lastPosition;

                $lastPosition = $this->moveTail($item, $currentTailPosition);

                if ($this->debug) {
                    $this->info(sprintf("Head %d:%d", $item[0], $item[1]));
                    $this->info(sprintf("Tail %d:%d", $lastPosition[0], $lastPosition[1]));
                    $this->info("");
                }

                return $lastPosition;
            });
        }

        return $positions->unique();
    }

    private function moveTail($headPosition, $tailPosition): array
    {
        if (
            $headPosition == $tailPosition
            || (abs($headPosition[0] - $tailPosition[0]) < 2
            && abs($headPosition[1] - $tailPosition[1]) < 2)
        ) {
            return $tailPosition;
        }

        if ($headPosition[0] === $tailPosition[0]) {
            // same
        } elseif ($headPosition[0] - $tailPosition[0] > 0) {
            $tailPosition[0]++;
        } else {
            $tailPosition[0]--;
        }

        if ($headPosition[1] === $tailPosition[1]) {
            // same
        } elseif ($headPosition[1] - $tailPosition[1] > 0) {
            $tailPosition[1]++;
        } else {
            $tailPosition[1]--;
        }

        return $tailPosition;
    }

    private function parsePositions(array $lines): Collection
    {
        $x = 0;
        $y = 0;

        $positions = [[0, 0]];

        foreach ($lines as $line) {
            $parts = explode(" ", $line);
            $direction = $parts[0];

            for($i = 0; $i < (int) $parts[1]; $i++) {
                $moveY = 0;
                $moveX = 0;
                switch ($direction) {
                    case "U":
                        $moveY++;
                        break;
                    case "D":
                        $moveY--;
                        break;
                    case "L":
                        $moveX--;
                        break;
                    case "R":
                        $moveX++;
                        break;
                }

                $x += $moveX;
                $y += $moveY;

                $positions[] = [$x, $y];
            }
        }

        return new Collection($positions);
    }
}
