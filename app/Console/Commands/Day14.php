<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;

class Day14 extends Command
{
    private static string $SAND = "s";
    private static string $ROCK = "#";
    private static string $AIR = ".";

    private static int $SAND_ORIGIN = 500;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day14';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grid';

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
            ->get('input/day14/input');
        
        $rockCoords = collect(explode("\n", $contents))
            ->map(fn($item) => $this->parseToCoords($item));
        
        $grid = $this->addRocks($this->buildGrid($rockCoords), $rockCoords);

        $this->debug = false;

        $i = 0;
        while (true) {
            try {
                $grid = $this->drop($grid, self::$SAND_ORIGIN, 0);
            } catch (Exception $e) {
                break;
            }

            $i++;
        }

        $this->info("Part 1: " . $i);
        
        return Command::SUCCESS;
    }

    private function drop(Collection $grid, int $x, int $y): Collection
    {
        $this->debug("Going to try dropping %d:%d", $x, $y);
        if (!$grid->has($y) || !$grid->get($y)->has($x)) {
            throw new Exception("Out of range");
        }

        $position = $grid->get($y)->get($x);

        if ($position !== self::$AIR) {
            throw new Exception("Position not free");
        }

        foreach ($grid->skip($y) as $key => $line) {
            $position = $line->get($x);

            if ($position !== self::$AIR) {
                $key--;
                break;
            }
        }

        foreach ([-1, 1] as $xModifier) {
            try {
                $this->debug("Trying %s:%s", $x + $xModifier, $key + 1);
                $grid = $this->drop($grid, $x + $xModifier, $key + 1);
                $this->debug("Dropped at %s:%s", $x + $xModifier, $key + 1);
                return $grid;
            } catch (Exception $e) {
                if ($e->getMessage() === "Out of range") {
                    throw $e;
                }
            }
        }

        $this->debug("Dropping at %s:%s", $x, $key);
        $grid->get($key)->put($x, self::$SAND);

        return $grid;
    }

    private function addRocks(Collection $grid, Collection $rockCoords): Collection
    {
        foreach ($rockCoords as $group) {
            $firstOperation = $group->shift();
            $x = $firstOperation[0];
            $y = $firstOperation[1];

            foreach ($group as $operation) {
                foreach (range($x, $operation[0]) as $drawX) {
                    foreach (range($y, $operation[1]) as $drawY) {
                        $grid[$drawY][$drawX] = self::$ROCK;
                    }
                }

                $x = $operation[0];
                $y = $operation[1];
            }
        }

        return $grid;
    }

    private function buildGrid(Collection $rockCoords): Collection
    {
        $flattened = $rockCoords->flatten(1);
        $minX = $flattened->map(fn($item) => $item[0])
            ->min();
        $maxX = $flattened->map(fn($item) => $item[0])
            ->max();
        $minY = 0;
        $maxY = $flattened->map(fn($item) => $item[1])
            ->max();
        
        $emptyRow = collect(range($minX, $maxX))
            ->flip()
            ->map(fn($item) => self::$AIR);
        
        return collect(range($minY, $maxY))
            ->map(fn($item) => collect($emptyRow));
    }

    private function renderGrid(Collection $grid): void
    {
        $grid->each(fn($item) => $this->info($item->implode("")));
    }

    private function parseToCoords(string $input): Collection
    {
        return collect(explode(" -> ", $input))
            ->map(fn($item) => explode(",", $item))
            ->map(fn($item) => array_map(fn($i) => intval($i), $item));
    }

    private function debug(...$params): void
    {
        if ($this->debug)
            $this->info(call_user_func_array("sprintf", $params));
    }
}
