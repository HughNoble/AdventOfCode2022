<?php

namespace App\Console\Commands;

use Aoc\Challenge\Day2 as Day2Challenge;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;

class Day2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Day 2 logic';

    private FilesystemManager $filesystemManager;
    private Day2Challenge $challenge;

    public function __construct(FilesystemManager $filesystemManager, Day2Challenge $challenge)
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
            ->get('input/day2/part1');
        
        $score = collect(explode("\n", $contents))
            ->map(fn($input) => $this->stringToScore($input))
            ->sum();
        
        dd($score);

        $this->info(sprintf("Score: ", $score));
        
        return Command::SUCCESS;
    }

    private function stringToScore(string $input): int
    {
        if (strlen($input) != 3) {
            return 0;
        }

        return $this->challenge->scoreRound(
            substr($input, 2, 1),
            substr($input, 0, 1)
        );
    }
}
