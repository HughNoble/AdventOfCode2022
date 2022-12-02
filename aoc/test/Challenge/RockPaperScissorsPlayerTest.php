<?php

namespace Aoc\Test\Challenge;

use Aoc\Challenge\RockPaperScissorsPlayer;
use PHPUnit\Framework\TestCase;

class RockPaperScissorsPlayerTest extends TestCase
{
    private static string $STRATEGY_LOSE = "X";
    private static string $STRATEGY_DRAW = "Y";
    private static string $STRATEGY_WIN = "Z";

    private static string $PLAYER_1_ROCK = "A";
    private static string $PLAYER_1_PAPER = "B";
    private static string $PLAYER_1_SCISSORS = "C";

    private static string $PLAYER_2_ROCK = "X";
    private static string $PLAYER_2_PAPER = "Y";
    private static string $PLAYER_2_SCISSORS = "Z";

    public function __construct()
    {
        parent::__construct();
        $this->challenge = new RockPaperScissorsPlayer;
    }

    public function testDraw()
    {
        $matrix = [
            self::$PLAYER_1_ROCK => self::$PLAYER_2_ROCK,
            self::$PLAYER_1_PAPER => self::$PLAYER_2_PAPER,
            self::$PLAYER_1_SCISSORS => self::$PLAYER_2_SCISSORS,
        ];

        foreach ($matrix as $input => $expected) {
            $this->assertEquals(
                $expected,
                $this->challenge->pickResponse(
                    self::$STRATEGY_DRAW,
                    $input
                )
            );
        }
    }

    public function testLose()
    {
        $matrix = [
            self::$PLAYER_1_ROCK => self::$PLAYER_2_SCISSORS,
            self::$PLAYER_1_PAPER => self::$PLAYER_2_ROCK,
            self::$PLAYER_1_SCISSORS => self::$PLAYER_2_PAPER,
        ];

        foreach ($matrix as $input => $expected) {
            $this->assertEquals(
                $expected,
                $this->challenge->pickResponse(
                    self::$STRATEGY_LOSE,
                    $input
                )
            );
        }
    }

    public function testWin()
    {
        $matrix = [
            self::$PLAYER_1_ROCK => self::$PLAYER_2_PAPER,
            self::$PLAYER_1_PAPER => self::$PLAYER_2_SCISSORS,
            self::$PLAYER_1_SCISSORS => self::$PLAYER_2_ROCK,
        ];

        foreach ($matrix as $input => $expected) {
            $this->assertEquals(
                $expected,
                $this->challenge->pickResponse(
                    self::$STRATEGY_WIN,
                    $input
                )
            );
        }
    }
}
