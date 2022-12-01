<?php

namespace Aoc\Test\Challenge;

use PHPUnit\Framework\TestCase;
use Aoc\Challenge\Day1;
use Aoc\Model\Elf;

class Day1Test extends TestCase
{
    public function testSingleFood()
    {
        $challenge = new Day1;
        $challenge->addElf(new Elf([100]));
        $this->assertEquals(100, $challenge->getHighestCalories());
    }

    public function testMultipleElves()
    {
        $challenge = new Day1;
        $challenge->addElf(new Elf([100]));
        $challenge->addElf(new Elf([200]));
        $this->assertEquals(200, $challenge->getHighestCalories());
    }

    public function testMultipleElvesMultipleFood()
    {
        $challenge = new Day1;
        $challenge->addElf(new Elf([100]));
        $challenge->addElf(new Elf([100, 400]));
        $challenge->addElf(new Elf([200]));
        $this->assertEquals(500, $challenge->getHighestCalories());
    }

    public function testGettingTopX()
    {
        $challenge = new Day1;
        $challenge->addElf(new Elf([100]));
        $challenge->addElf(new Elf([100, 400]));
        $challenge->addElf(new Elf([600]));
        $challenge->addElf(new Elf([10, 300]));
        $challenge->addElf(new Elf([200]));
        $challenge->addElf(new Elf([200]));
        $challenge->addElf(new Elf([200]));;
        $this->assertEquals(1410, $challenge->sumCaloriesForTop(3));
    }
}
