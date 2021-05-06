<?php

declare(strict_types=1);

namespace App\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

/* use function Mos\Functions\{
    destroySession,
    redirectTo,
    renderView,
    renderTwigView,
    sendResponse,
    url
}; */

/**
 * Class Dice.
 */
class DiceHand
{
    protected array $dices; //sparar varje tärning i array
    private int $sum;
    private int $nrOfDices;

    public function __construct(int $nrOfDices)
    {
        $this->nrOfDices = $nrOfDices; // antal tärningar man vill kasta

        for ($i = 1; $i <= $nrOfDices; $i++) {
            $this->dices[$i] = new GraphicalDice();
        }
    }

    public function roll(): void
    {
        $this->sum = 0;

        for ($i = 1; $i <= $this->nrOfDices; $i++) {
            $this->sum += $this->dices[$i]->roll();
        }
    }


    public function getLastRoll(): string
    {
        $res = "";

        for ($i = 1; $i <= $this->nrOfDices; $i++) {
            $res .= $this->dices[$i]->getLastRoll() . ", ";
        }
        return $res . " = " . $this->sum;
    }

    public function getLastRollWithoutSum(): array
    {
        $res = [];

        for ($i = 1; $i <= $this->nrOfDices; $i++) {
            array_push($res, $this->dices[$i]->getLastRoll());
        }
        return $res;
    }


    public function getGraphicalDices(): array
    {
        $res = [];
        for ($i = 1; $i <= $this->nrOfDices; $i++) {
            $res[] .= $this->dices[$i]->graphic();
        }
        return $res;
    }


    public function getSum(): int
    {

        return $this->sum;
    }

    public function checkNumber($number): string
    {
        $returnedmess = "Du kan välja att kasta igen eller stanna";

        if ($number > 21) {
            $returnedmess = "Du fick " . $number . ". Du förlorade";
            $_SESSION["compPoints"] += 1;
            $_SESSION["playerSum"] = 0;
            $_SESSION["compSum"] = 0;
        } else if ($number == 21) {
            $returnedmess = $number . "!!!!, Om datorn ej får 21 vinner du rundan";
            $_SESSION["playerPoints"] += 1;
            $_SESSION["playerSum"] = 0;
            $_SESSION["compSum"] = 0;
        }

        return $returnedmess;
    }

    public function setNrOfDice($nrOfDices)
    {
        $this->nrOfDices = $nrOfDices;
    }

    public function getNrOfDice(): int
    {
        return $this->nrOfDices;
    }
}
