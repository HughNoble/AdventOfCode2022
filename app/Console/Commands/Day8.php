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
        $verticalGrid = $this->horizontalGridToVertical($horizontalGrid);

        $visibleHorizontally = $this->getHorizontallyVisiblePositions($horizontalGrid);
        $visibleVertically = $this->getHorizontallyVisiblePositions($verticalGrid);
        
        $part1 = $visibleHorizontally->merge($visibleVertically)->unique()->count();
        $this->info("Part 1: " . $part1);
        
        return Command::SUCCESS;
    }

    private function parseHorizontal(array $lines): Collection
    {
        return (new Collection($lines))
            ->map(fn($line) => str_split($line))
            ->map(fn($line) => new Collection($line))
            ->map(fn($line, $key) => $line->map(fn($item, $index) => $key . "-" . $index . "-" . $item));
    }

    private function horizontalGridToVertical(Collection $horizontalGrid): Collection
    {
        return $horizontalGrid
            ->keys()
            ->map(fn($item) => $horizontalGrid->pluck($item));
    }

    private function getHorizontallyVisiblePositions(Collection $grid): Collection
    {
        return $grid
            ->map(fn($item) => $this->getVisibleInRow($item))
            ->flatten();
    }

    private function getVisibleInRow(Collection $row): Collection
    {
        $previous = -1;

        $counted = [];

        foreach ($row as $key => $value) {
            $height = substr($value, -1);
            if ($height > $previous) {
                $counted[$key] = $value;
                $previous = $height;
            }
        }

        $previous = -1;

        foreach ($row->reverse() as $key => $value) {
            $height = substr($value, -1);
            if ($height > $previous) {
                $counted[$key] = $value;
                $previous = $height;
            }
        }

        return collect($counted)->flatten();
    }
}
