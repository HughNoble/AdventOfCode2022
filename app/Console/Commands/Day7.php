<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;

class Day7 extends Command
{
    private static int $DISK_SIZE = 70000000;
    private static int $UPDATE_SIZE = 30000000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day7';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Time to parse some files';

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
            ->get('input/day7/input');
        
        $filesystem = $this->parse(explode("\n", $contents));

        $part1 = $filesystem->where(fn($filesize) => $filesize <= 100000)
            ->sum();

        $this->info($part1);

        $freeSpace = self::$DISK_SIZE - (int) $filesystem->get("/");
        $requiredSpace = self::$UPDATE_SIZE - $freeSpace;
        
        $part2 = $filesystem->where(fn($filesize) => $filesize >= $requiredSpace)
            ->sort()
            ->first();
        
        $this->info($part2);

        return Command::SUCCESS;
    }

    private function parse(array $commands): Collection
    {
        $currentDir = "";
        $filesystem = []; 

        foreach ($commands as $key => $line) {
            $parts = explode(" ", $line);

            if ($parts[0] === "$" && $parts[1] === "cd") {
                if ($parts[2] === "/") {
                    $currentDir = "/";
                } elseif ($parts[2] === "..") {
                    $currentDir = $this->getPreviousDir($currentDir);
                } elseif($currentDir === "/") {
                    $currentDir = "/" . $parts[2];
                } else {
                    $currentDir .= "/" . $parts[2];
                }

                continue;
            }

            if ($parts[0] === "$" && $parts[1] === "ls") {
                $filesystem[$currentDir] = $this->sizeOfDir(array_slice($commands, $key));
            }
        }

        return new Collection($filesystem);
    }

    private function sizeOfDir(array $commands): int
    {
        $size = 0;
        $dirLevel =0;

        foreach ($commands as $line) {
            $parts = explode(" ", $line);

            if ($parts[1] == "cd") {
                $dirLevel += $parts[2] === ".." ? -1 : +1;
                if ($dirLevel === -1) {
                    break;
                }
            }

            if (is_numeric($parts[0])) {
                $size += $parts[0];
            }
        }

        return $size;
    }

    private function getPreviousDir($currentDir): string
    {
        $dir = substr($currentDir, 0, strrpos($currentDir, "/"));
        return $dir ?: "/";
    }
}
