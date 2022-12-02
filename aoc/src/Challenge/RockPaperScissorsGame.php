<?php

namespace Aoc\Challenge;

class RockPaperScissorsGame
{
    private static string $ROCK = "ROCK";
    private static string $PAPER = "PAPER";
    private static string $SCISSORS = "SCISSORS";

    private static int $SCORE_LOSE = 0;
    private static int $SCORE_DRAW = 3;
    private static int $SCORE_WIN = 6;

    private static int $BASE_SCORE_ROCK = 1;
    private static int $BASE_SCORE_PAPER = 2;
    private static int $BASE_SCORE_SCISSORS = 3;

    public function scoreRound($player1GuessString, $player2GuessString): int
    {
        $player1Guess = self::guessFromString($player1GuessString);
        $player2Guess = self::guessFromString($player2GuessString);

        $baseScore = self::${"BASE_SCORE_$player1Guess"};

        if ($player1Guess === $player2Guess) {
            return self::$SCORE_DRAW + $baseScore;
        }

        $matrix = [
            self::$ROCK => self::$SCISSORS,
            self::$PAPER => self::$ROCK,
            self::$SCISSORS => self::$PAPER,
        ];

        $score = $player2Guess == $matrix[$player1Guess]
            ? self::$SCORE_WIN
            : self::$SCORE_LOSE;

        return $score + $baseScore;
    }

    private static function guessFromString($stringGuess): string
    {
        $matrix = [
            "A" => self::$ROCK,
            "X" => self::$ROCK,

            "B" => self::$PAPER,
            "Y" => self::$PAPER,

            "C" => self::$SCISSORS,
            "Z" => self::$SCISSORS,
        ];

        return $matrix[$stringGuess];
    }
}
