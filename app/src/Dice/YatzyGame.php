<?php

declare(strict_types=1);

namespace App\Dice;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

use function Mos\Functions\{
    renderView,
    sendResponse
    };

class YatzyGame extends DiceHand
{
    public ?int $sum = 0;
    public ?int $whatRound = 0;
    public ?int $whatThrow = 0;
    public ?int $roundValue = 0;

    public function __construct($nrOfDices)
    {
        parent::__construct($nrOfDices);
    }

    public function whatRound(): int
    {
        if (isset($_SESSION["round"])) {
            $this->whatRound = $_SESSION["round"];
        } else if (!isset($_SESSION["round"])) {
            $this->whatRound = 0;
        }
        return $this->whatRound;
    }

    public function whatThrow(): int
    {
        if (isset($_SESSION["throws"])) {
            $this->whatThrow = $_SESSION["throws"];
        } else if (!isset($_SESSION["throws"])) {
            $this->whatThrow = 0;
        }
        return $this->whatThrow;
    }

    public function newRoundOrNot(): string
    {
        $messThrowDice = "";
        echo($this->whatThrow);

        if ($this->whatThrow == 3) {
            echo($this->whatThrow);
            $messThrowDice = "<b> Kasta om alla tärningar, ny runda! </b>";
        }
        return $messThrowDice;
    }

    public function savedDices(): array
    {
        $savedDicesGraph = [];
        $savedDices = [];
        foreach ($_POST as $value) :
            if ($value != "KASTA") {
                array_push($savedDices, $value);
                array_push($savedDicesGraph, "dice-" . strval($value));
            }
            ?>
        <?php endforeach;
        $_SESSION["NrOfDicesToThrowNextRound"] = 5 - count($savedDices);
        $_SESSION["savedDices"] = $savedDices; //sparar undan arrayn i sessionen så att jag kan ta ut den i forloop i spelet

        return $savedDicesGraph;
    }

    public function checkScore(): int
    {
        $this->roundValue = 0;
        foreach ($_SESSION["savedDices"] as $value) :
            if ($this->whatRound == $value) {
                $this->roundValue += $value;
            }
        endforeach;
        if ($this->whatThrow == 3) {
            $valueOnHandNotSaved = $this->getLastRollWithoutSum();

            foreach ($valueOnHandNotSaved as $value) {
                if ($this->whatRound == $value) {
                    $this->roundValue += $value;
                }
            }
            $this->sum += $this->roundValue;
        }
        return $this->roundValue;
    }

    public function getSum(): int
    {
        return $this->sum;
    }


    public function returnMess(): string
    {
        $returnMess = "";
        if ($this->whatRound == 0) {
            $returnMess = "Börja spelet";
        } else if ($this->whatRound == 1) {
            $returnMess = "Du ska ha ettor";
        } else if ($this->whatRound == 2) {
            $returnMess = "Du ska ha tvåor";
        } else if ($this->whatRound == 3) {
            $returnMess = "Du ska ha treor";
        } else if ($this->whatRound == 4) {
            $returnMess = "Du ska ha fyror";
        } else if ($this->whatRound == 5) {
            $returnMess = "Du ska ha femmor";
        } else if ($this->whatRound == 6) {
            $returnMess = "Du ska ha sexor";
        } else if ($this->whatRound == 7) {
            $returnMess = "Du är klar med spelet";
        }
        return $returnMess;
    }
}

