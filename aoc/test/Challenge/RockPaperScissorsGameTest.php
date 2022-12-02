<?php

namespace Aoc\Test;

use Aoc\Challenge\RockPaperScissorsGame;
use PHPUnit\Framework\TestCase;

class RockPaperScissorsGameTest extends TestCase
{
    private static string $PLAYER_1_ROCK = "A";
    private static string $PLAYER_1_PAPER = "B";
    private static string $PLAYER_1_SCISSORS = "C";

    private static string $PLAYER_2_ROCK = "X";
    private static string $PLAYER_2_PAPER = "Y";
    private static string $PLAYER_2_SCISSORS = "Z";

    private static string $ROCK = "ROCK";
    private static string $PAPER = "PAPER";
    private static string $SCISSORS = "SCISSORS";

    private static int $SCORE_LOSE = 0;
    private static int $SCORE_DRAW = 3;
    private static int $SCORE_WIN = 6;

    private static int $BASE_SCORE_ROCK = 1;
    private static int $BASE_SCORE_PAPER = 2;
    private static int $BASE_SCORE_SCISSORS = 3;

    private RockPaperScissorsGame $challenge;

    public function __construct()
    {
        parent::__construct();
        $this->challenge = new RockPaperScissorsGame;
    }

    public function testBothRockDraw()
    {
        $this->assertDraw(self::$ROCK);
    }

    public function testBothPaperDraw()
    {
        $this->assertDraw(self::$PAPER);
    }

    public function testBothScissorsDraw()
    {
        $this->assertDraw(self::$SCISSORS);
    }

    public function testPaperBeatsRock()
    {
        $this->assertBeats(self::$PAPER, self::$ROCK);
    }

    public function testScissorsBeatsPaper()
    {
        $this->assertBeats(self::$SCISSORS, self::$PAPER);
    }

    public function testRockBeatsScissors()
    {
        $this->assertBeats(self::$ROCK, self::$SCISSORS);
    }

    private function assertDraw(string $guess)
    {
        $this->assertEquals(
            self::$SCORE_DRAW + self::${"BASE_SCORE_$guess"},
            $this->challenge->scoreRound(
                self::${"PLAYER_1_$guess"},
                self::${"PLAYER_2_$guess"}
            )
        );
    }

    private function assertBeats($guess1, $guess2)
    {
        $this->assertScore(
            self::${"BASE_SCORE_$guess1"} + self::$SCORE_WIN,
            self::${"PLAYER_1_$guess1"},
            self::${"PLAYER_2_$guess2"}
        );

        $this->assertScore(
            self::${"BASE_SCORE_$guess2"} + self::$SCORE_LOSE,
            self::${"PLAYER_1_$guess2"},
            self::${"PLAYER_2_$guess1"}
        );
    }

    private function assertScore(int $expected, string $player1Guess, string $player2Guess)
    {
        $this->assertEquals(
            $expected,
            $this->challenge->scoreRound($player1Guess, $player2Guess)
        );
    }
}
