<?php

namespace App\Console\Commands;

use Aoc\Challenge\Day3 as ChallengeDay3;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;

class Day3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private FilesystemManager $filesystemManager;
    private ChallengeDay3 $challenge;

    public function __construct(FilesystemManager $filesystemManager, ChallengeDay3 $challenge)
    {
        parent::__construct();
        $this->filesystemManager = $filesystemManager;
        $this->challenge = $challenge;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $contents = $this->filesystemManager->disk('local')
            ->get('input/day3/part1');

        $items = collect(explode("\n", $contents))
            ->filter(fn($item) => strlen($item) > 0)
            ->map(fn($item) => str_split($item))
            ->filter(fn($item) => count($item) > 0)
            ->map(fn($item) => $this->challenge->getCommonItems($item))
            ->map(fn($items) => $this->challenge->scoreItems($items))
            ->sum();
        
        $this->info("Sum: " . $items);

        return Command::SUCCESS;
    }
}
