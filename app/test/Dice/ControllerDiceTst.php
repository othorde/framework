<?php

declare(strict_types=1);

namespace App\Controller;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Sample.
 */
class ControllerDiceTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new Dice();
        $this->assertInstanceOf("\Mos\Dice\Dice", $controller);
    }

    /**
     * Kollar att jag får tillbaka en int av random funktion
     * att den ej är över 6 eller under 1
     */
    public function testRollReturnsInt()
    {
        $controller = new Dice();
        $res = $controller->roll();
        $this->assertIsInt($res);
        $this->assertGreaterThan(0, $res);
        $this->assertLessThan(7, $res);
    }
    /* Kollar att jag får tillbaka samma resultat som det jag kasta */
    public function testRollReturnsSameAsGetLastRoll()
    {
        $controller = new Dice();
        $res = $controller->roll();
        $res2 = $controller->getLastRoll();

        $this->assertSame($res, $res2);
    }
}
