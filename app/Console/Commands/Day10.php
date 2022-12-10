<?php

namespace App\Console\Commands;

use Aoc\Challenge\DisplayProcessor;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;

class Day10 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day10';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Day 10';

    private FilesystemManager $filesystemManager;
    private DisplayProcessor $displayProcessor;

    public function __construct(
        FilesystemManager $filesystemManager,
        DisplayProcessor $displayProcessor
    )
    {
        parent::__construct();
        $this->filesystemManager = $filesystemManager;
        $this->displayProcessor = $displayProcessor;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $contents = $this->filesystemManager->disk('local')
            ->get('input/day10/input');
        
        $lines = explode("\n", $contents);

        $fullStack = collect($this->displayProcessor->processInstructions($lines));
        
        $stack = $fullStack->map(fn($x, $i) => $x * $i);

        $first20 = $stack->shift(20);
        $filtered = collect($first20->last())
            ->merge($stack->nth(40, 39));
        
        $part1 = $filtered->sum();

        $this->info($part1);

        $line = "";

        foreach ($fullStack as $cycle => $position) {
            $drawing = strlen($line) + 1;

            $line .= ($drawing >= (int) $position && $drawing <= (int) $position + 2)
                ? "#"
                : ".";
            
            if ((int) $cycle %40 === 0) {
                $this->info($line);
                $line = "";
            }
        }

        return Command::SUCCESS;
    }
}
