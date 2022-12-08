<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;

class Day8 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day8';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The grids begin';

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
            ->get('input/day8/input');
        
        $horizontalGrid = $this->parseHorizontal(explode("\n", $contents));
        
        $part1 = $this->countVisibleFromOutsideInGrid($horizontalGrid);
        $this->info("Part 1: " . $part1);

        $part2 = $this->findBestScenicValue($horizontalGrid);
        $this->info("Part 2: " . $part2);
        
        return Command::SUCCESS;
    }

    private function parseHorizontal(array $lines): Collection
    {
        return (new Collection($lines))
            ->map(fn($line) => str_split($line))
            ->map(fn($line) => new Collection($line));
    }

    private function countVisibleFromOutsideInGrid(Collection $grid): int
    {
        $xSize = $grid->first()->count();
        $ySize = $grid->count();

        $visible = 0;

        for ($x = 0; $x < $xSize; $x++) {
            for ($y = 0; $y < $ySize; $y++) {
                $myValue = $this->getHeightForPosition($grid, $x, $y);
                
                $visibleFromLeft = $this->calculateOutsideVisibility(
                    fn ($i) => $this->getHeightForPosition($grid, $x, $y + $i),
                    $myValue
                );
                $visibleFromRight = $this->calculateOutsideVisibility(
                    fn ($i) => $this->getHeightForPosition($grid, $x, $y - $i),
                    $myValue
                );
                $visibleFromTop = $this->calculateOutsideVisibility(
                    fn ($i) => $this->getHeightForPosition($grid, $x - $i, $y),
                    $myValue
                );
                $visibleFromBottom = $this->calculateOutsideVisibility(
                    fn ($i) => $this->getHeightForPosition($grid, $x + $i, $y),
                    $myValue
                );

                if ($visibleFromLeft || $visibleFromRight || $visibleFromTop || $visibleFromBottom) {
                    $visible++;
                }
            }
        }

        return $visible;
    }

    private function findBestScenicValue(Collection $grid)
    {
        $xSize = $grid->first()->count();
        $ySize = $grid->count();

        $bestScenicValue = 0;

        for ($x = 1; $x < $xSize; $x++) {
            for ($y = 1; $y < $ySize; $y++) {
                $myValue = $this->getHeightForPosition($grid, $x, $y);
                
                $valueRight = $this->calculateVisibility(
                    fn($i) => $this->getHeightForPosition($grid, $x, $y + $i),
                    $myValue
                );
                
                $valueLeft = $this->calculateVisibility(
                    fn($i) => $this->getHeightForPosition($grid, $x, $y - $i),
                    $myValue
                );

                $valueDown = $this->calculateVisibility(
                    fn($i) => $this->getHeightForPosition($grid, $x + $i, $y),
                    $myValue
                );

                $valueUp = $this->calculateVisibility(
                    fn($i) => $this->getHeightForPosition($grid, $x - $i, $y),
                    $myValue
                );

                $finalValue = $valueUp * $valueLeft * $valueRight * $valueDown;
                if ($finalValue > $bestScenicValue) {
                    $bestScenicValue = $finalValue;
                }
            }
        }

        return $bestScenicValue;
    }

    private function getHeightForPosition(Collection $grid, int $x, int $y): ?int
    {
        if ($x < 0 || $y < 0) {
            return null;
        }

        $entry = $grid->get($x, new Collection())->get($y);
        
        return $entry;
    }

    private function calculateVisibility(callable $operation, int $myValue)
    {
        $i = 0;
        $visible = 0;

        while(true) {
            $i++;
            $value = $operation($i);

            if ($value === null) {
                break;
            }

            $visible++;
            
            if ($value >= $myValue) {
                break;
            }
        }

        return $visible;
    }

    private function calculateOutsideVisibility(callable $operation, int $myValue)
    {
        $i = 0;

        while(true) {
            $i++;
            $value = $operation($i);

            if ($value === null) {
                break;
            }
            
            if ($value >= $myValue) {
                return false;
            }
        }

        return true;
    }
}
