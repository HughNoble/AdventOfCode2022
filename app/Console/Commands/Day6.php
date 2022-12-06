<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\FilesystemManager;

class Day6 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day6';

    private FilesystemManager $filesystemManager;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Day 6';

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
            ->get('input/day6/input');
        
        $input = str_split($contents);
        
        $this->info("Start " . $this->getStartIndex($input));

        return Command::SUCCESS;
    }

    private function getStartIndex(array $input): int
    {
        foreach ($input as $key => $char) {
            $buffer = collect([
                $char,
                $input[$key + 1],
                $input[$key + 2],
                $input[$key + 3],
            ]);

            if ($buffer->unique()->count() === 4) {
                return $key + 4;
            }
        }
    }
}
