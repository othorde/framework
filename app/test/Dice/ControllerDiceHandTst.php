<?php

declare(strict_types=1);

namespace App\Controller;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Sample.
 */
class ControllerDiceHandTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new DiceHand(5);
        $this->assertInstanceOf("\Mos\Dice\DiceHand", $controller);
    }

    /* testar metod dicehandroll genom att kolla att jag kan hämta värden i getlastroll
        Kollar också så att metoden är null, då det är void metod samt att det returneras
        en sträng av värden
    */

    public function testDiceHandRoll()
    {
        $controller = new DiceHand(5);
        $this->assertInstanceOf("\Mos\Dice\DiceHand", $controller);
        $voidMethod = $controller->roll();
        $this->assertNull($voidMethod);
        $value = $controller->getLastRoll();
        $this->assertIsString($value);
    }
    /* testar att jag får tebx en array med 5 värden i  */
    public function testGetLastRollWithoutSum()
    {
        $controller = new DiceHand(5);
        $this->assertInstanceOf("\Mos\Dice\DiceHand", $controller);
        $controller->roll();
        $arrayOfDiceValues = $controller->getLastRollWithoutSum();
        $this->assertIsArray($arrayOfDiceValues);
        $this->assertEquals(count($arrayOfDiceValues), 5);
    }
    /* testar att jag får tebx en array med 5 värden i  */

    public function testGetGraphicalDices()
    {
        $controller = new DiceHand(5);
        $this->assertInstanceOf("\Mos\Dice\DiceHand", $controller);
        $controller->roll();
        $arrOfGraphDieValue = $controller->getGraphicalDices();
        $this->assertIsArray($arrOfGraphDieValue);
        $this->assertEquals(count($arrOfGraphDieValue), 5);
    }

    /* kontrollerar att metoden ger mig summan som måste vara i intervallet 3-30 då 5*6 = 30 och 1*5 = 5 */

    public function testGetSum()
    {
        $controller = new DiceHand(5);
        $this->assertInstanceOf("\Mos\Dice\DiceHand", $controller);
        $controller->roll();

        $getSum = $controller->getSum();
        $this->assertLessThanOrEqual(30, $getSum);
        $this->assertGreaterThanOrEqual(5, $getSum);
    }

    /* kontrollerar att jag kan ändra värdet mha setNrOfDice till 2 från 5 */
    public function testSetNrOfDice()
    {
        $controller = new DiceHand(5);
        $controller->roll();
        $arrayOfDiceValues = $controller->getLastRollWithoutSum();
        $this->assertEquals(count($arrayOfDiceValues), 5);

        $controller->setNrOfDice(2);
        $arrayOfDiceValues2 = $controller->getLastRollWithoutSum();

        $this->assertEquals(count($arrayOfDiceValues2), 2);
    }

    /* testar att hämta 3 olika värde med get.  */
    public function testGetNrOfDice()
    {
        $controller = new DiceHand(5);
        $getNrOfDice = $controller->getNrOfDice();
        $this->assertEquals($getNrOfDice, 5);

        $controller->setNrOfDice(2);
        $getNrOfDice = $controller->getNrOfDice();
        $this->assertEquals($getNrOfDice, 2);

        $controller->setNrOfDice(1);
        $getNrOfDice = $controller->getNrOfDice();
        $this->assertEquals($getNrOfDice, 1);
    }


    /* kontrollerar att metoden ger mig summan som måste vara i intervallet 3-30 då 5*6 = 30 och 1*5 = 5 */

    public function testCheckNumber()
    {
        $controller = new DiceHand(5);
        $number = 22;
        $_SESSION["compPoints"] = 0;
        $_SESSION["playerSum"] = 0;
        $_SESSION["playerPoints"] = 0;

        $lower = $controller->checkNumber(1);
        $this->assertEquals("Du kan välja att kasta igen eller stanna", $lower);

        $number2 = 21;
        $mid = $controller->checkNumber($number2);
        $this->assertEquals($mid, $number2 . "!!!!, Om datorn ej får 21 vinner du rundan");

        $higher = $controller->checkNumber(22);
        $this->assertEquals("Du fick " . $number . ". Du förlorade", $higher);
    }
}
