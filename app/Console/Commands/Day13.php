<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;

class Day13 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day13';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sort this';

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
            ->get('input/day13/input');
        
        $pairs = collect(explode("\n\n", $contents))
            ->map(fn($item) => $this->stringToPair($item));
        
        $part1 = $pairs->map(fn($item) => $this->compare($item[0], $item[1]))
            ->filter(fn($item) => $item > 0)
            ->keys()
            ->map(fn($item) => $item + 1)
            ->sum();
        
        $this->info("Part 1: " . $part1);

        $sorted = $pairs->flatten(1)
            ->add([6])
            ->add([2])
            ->sort(fn($left, $right) => $this->compare($left, $right))
            ->reverse()
            ->values();
        
        $decoderKey1 = $sorted->filter(fn($item) => $item === [2])
            ->keys()
            ->map(fn($item) => $item + 1)
            ->first();

        $decoderKey2 = $sorted->filter(fn($item) => $item === [6])
            ->keys()
            ->map(fn($item) => $item + 1)
            ->first();

        $part2 = $decoderKey1 * $decoderKey2;

        $this->info("Part 2: " . $part2);

        return Command::SUCCESS;
    }

    private function compare($left, $right)
    {
        if (is_array($left) && count($left) === 1) {
            $left = $left[0];
        }

        if (is_array($right) && count($right) === 1) {
            $right = $right[0];
        }

        if ($left == $right) {
            return 0;
        }

        if (is_int($left) && is_int($right)) {
            return $left > $right ? -1 : 1;
        }

        if (is_int($left)) {
            $left = [$left];
        }

        if (is_int($right)) {
            $right = [$right];
        }

        foreach ($left as $key => $item) {
            if (!isset($right[$key])) {
                return -1;
            }

            $result = $this->compare($item, $right[$key]);

            if ($result === 0) {
                continue;
            }

            return $result;
        }

        if (count($left) === count($right)) {
            return 0;
        }

        return count($left) > count($right) ? -1 : 1;
    }

    private function stringToPair(string $input): Collection
    {
        return collect(explode("\n", $input))
            ->map(fn($item) => $this->parseLine($item));
    }

    private function parseLine(string $input): array
    {
        return json_decode($input);
    }
}
