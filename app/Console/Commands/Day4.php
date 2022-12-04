<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;

class Day4 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day4';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            ->get('input/day4/part1');

        $overlaps = collect(explode("\n", $contents))
            ->map(fn($item) => $this->stringToRanges($item))
            ->filter(fn($item) => $this->fullyOverlaps($item))
            ->count();

        $this->info("Pairs overlapped: " . $overlaps);

        return Command::SUCCESS;
    }

    private function stringToRanges(string $input): array
    {
        return collect(explode(",", $input))
            ->map(fn($item) => explode("-", $item))
            ->toArray();
    }

    private function fullyOverlaps(array $pair): bool
    {
        $elf1 = $pair[0];
        $elf2 = $pair[1];

        return $this->pairContains($elf1, $elf2)
            || $this->pairContains($elf2, $elf1);
    }

    private function pairContains($elf1, $elf2)
    {
        $elf1Start = (int) $elf1[0];
        $elf1End = (int) $elf1[1];

        $elf2Start = (int) $elf2[0];
        $elf2End = (int) $elf2[1];

        return $elf1Start >= $elf2Start && $elf1End <= $elf2End;
    }
}
