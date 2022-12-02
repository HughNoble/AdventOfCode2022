<?php

namespace Aoc\Challenge;

class RockPaperScissorsPlayer
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

    public function pickResponse($strategy, $opponentGuess)
    {
        switch ($strategy) {
            case self::$STRATEGY_DRAW:
                $matrix = [
                    self::$PLAYER_1_ROCK => self::$PLAYER_2_ROCK,
                    self::$PLAYER_1_PAPER => self::$PLAYER_2_PAPER,
                    self::$PLAYER_1_SCISSORS => self::$PLAYER_2_SCISSORS,
                ];
                break;
            case self::$STRATEGY_LOSE:
                $matrix = [
                    self::$PLAYER_1_ROCK => self::$PLAYER_2_SCISSORS,
                    self::$PLAYER_1_PAPER => self::$PLAYER_2_ROCK,
                    self::$PLAYER_1_SCISSORS => self::$PLAYER_2_PAPER,
                ];
                break;
            case self::$STRATEGY_WIN:
                $matrix = [
                    self::$PLAYER_1_ROCK => self::$PLAYER_2_PAPER,
                    self::$PLAYER_1_PAPER => self::$PLAYER_2_SCISSORS,
                    self::$PLAYER_1_SCISSORS => self::$PLAYER_2_ROCK,
                ];
                break;
        }

        return $matrix[$opponentGuess];
    }
}
