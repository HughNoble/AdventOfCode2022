<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Aoc\Challenge\Day1 as Day1Challenge;
use Aoc\Model\Elf;

class Day1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Day 1 logic-';

    private FilesystemManager $filesystemManager;
    private Day1Challenge $challenge;

    public function __construct(FilesystemManager $filesystemManager, Day1Challenge $challenge)
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
            ->get('input/day1/part1');
        
        collect(explode("\n\n", $contents))
            ->map(fn($input) => $this->stringToElf($input))
            ->each(fn($elf) => $this->challenge->addElf($elf));

        $this->info(sprintf("Top: %s", $this->challenge->getHighestCalories()));
        $this->info(sprintf("Top 3: %s", $this->challenge->sumCaloriesForTop(3)));
        
        return Command::SUCCESS;
    }

    private function stringToElf(string $input): Elf
    {
        return new Elf(array_filter(explode("\n", $input))); 
    }
}
