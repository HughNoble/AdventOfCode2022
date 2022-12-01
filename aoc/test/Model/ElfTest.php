<?php

namespace Aoc\Test\Model;

use PHPUnit\Framework\TestCase;
use Aoc\Model\Elf;

class ElfTest extends TestCase
{
    public function testCanGetCalories()
    {
        $elf = new Elf([1000, 1000]);
        $this->assertEquals(2000, $elf->getTotalCaloriesCarried());
    }
}
