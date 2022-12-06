<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\FilesystemManager;

class Day6 extends Command
{
    private static int $PACKET_SIZE_PACKET_START = 4;
    private static int $PACKET_SIZE_MESSAGE_START = 14;

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

        $packet_start_position = $this->findPositionOfPacketStart(
            self::$PACKET_SIZE_PACKET_START,
            $input
        );
        
        $this->info("Start " . $packet_start_position);

        $message_start_position = $this->findPositionOfPacketStart(
            self::$PACKET_SIZE_MESSAGE_START,
            $input
        );
        
        $this->info("Message " . $message_start_position);

        return Command::SUCCESS;
    }

    private function findPositionOfPacketStart(int $num_unique, array $input): int
    {
        for ($i = 0; $i <= count($input); $i++) {
            $buffer = collect(array_slice($input, $i, $num_unique));

            if ($buffer->unique()->count() === $num_unique) {
                return $i + $num_unique;
            }
        }
    }
}
