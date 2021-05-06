<?php

declare(strict_types=1);

namespace App\Controller;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the controller Sample.
 */
class ControllerYatzyGameTest extends TestCase
{
    /**
     * Try to create the controller class.
     */
    public function testCreateTheControllerClass()
    {
        $controller = new YatzyGame(2);
        $this->assertInstanceOf("\Mos\Dice\YatzyGame", $controller);
        $this->assertInstanceOf("\Mos\Dice\DiceHand", $controller);
    }

    /* testar sätta round till ett värde och ser om det är vad som retuneras av metoden
    Testar else satsen genom att skicka in värdet null, vilket innebär att värdet ej är satt enligt docs,
    vilket gör att jag testar else satsen */
    public function testWhatRound()
    {
        $controller = new YatzyGame(2);
        $_SESSION["round"] = 4;
        $whatround = $controller->whatRound();
        $this->assertSame($whatround, $_SESSION["round"]);
        $_SESSION["round"] = null;
        $whatround = $controller->whatRound();
        $this->assertSame($whatround, 0);
    }

    /* samma som ovan fast throws */
    public function testWhatThrow()
    {
        $controller = new YatzyGame(3);
        $_SESSION["throws"] = 2;
        $whatThrow = $controller->whatThrow();
        $this->assertSame($whatThrow, $_SESSION["throws"]);
        $_SESSION["throws"] = null;
        $whatThrow = $controller->whatThrow();
        $this->assertSame($whatThrow, 0);
    }

    /* testar att den retunerar "" */
    public function testNewRoundOrNot()
    {
        $controller = new YatzyGame(3);
        $value3 = $controller->newRoundOrNot();
        $this->assertSame($value3, "");

        $controller = new YatzyGame(3);
        $controller->whatThrow = 3;
        $value = $controller->newRoundOrNot();
        $this->assertSame($value, "<b> Kasta om alla tärningar, ny runda! </b>");
    }

    /* testar att metoden gör om och tar bort KASTA samt retunerar rätt värde */
    public function testSavedDices()
    {
        $controller = new YatzyGame(3);
        array_push($_POST, "KASTA", "1", "2", "5", "3");
        $arr = ["dice-1", "dice-2", "dice-5", "dice-3"];

        $value3 = $controller->savedDices();
        $this->assertSame($value3, $arr);
    }

    public function testGetSum()
    {
        $controller = new YatzyGame(3);
        $getsum = $controller->getSum();
        $this->assertSame($getsum, 0);
    }

    public function testReturnMess()
    {
        $controller = new YatzyGame(3);
        $returnMess = $controller->returnMess();
        $this->assertSame($returnMess, "Börja spelet");

        $controller->whatRound = 1;
        $returnMess = $controller->returnMess();
        $this->assertSame($returnMess, "Du ska ha ettor");

        $controller->whatRound = 2;
        $returnMess = $controller->returnMess();
        $this->assertSame($returnMess, "Du ska ha tvåor");

        $controller->whatRound = 3;
        $returnMess = $controller->returnMess();
        $this->assertSame($returnMess, "Du ska ha treor");

        $controller->whatRound = 4;
        $returnMess = $controller->returnMess();
        $this->assertSame($returnMess, "Du ska ha fyror");

        $controller->whatRound = 5;
        $returnMess = $controller->returnMess();
        $this->assertSame($returnMess, "Du ska ha femmor");

        $controller->whatRound = 6;
        $returnMess = $controller->returnMess();
        $this->assertSame($returnMess, "Du ska ha sexor");

        $controller->whatRound = 7;
        $returnMess = $controller->returnMess();
        $this->assertSame($returnMess, "Du är klar med spelet");
    }

    public function testCheckScore()
    {
        $controller = new YatzyGame(5);
        array_push($_SESSION["savedDices"], 1, 2, 5, 3);
        $controller->whatRound = 3;
        $controller->whatThrow = 2;
        $getcheckScore = $controller->checkScore();
        $this->assertSame($getcheckScore, 6);
    }
}
