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
class Dice
{
    private ?int $roll = null;
    private ?int $faces = null;

    public function __construct() // ändra från att ta $faces som inparameter till hårdkordat värde på 6
    {
        $this->faces = 6;
    }

    public function roll(): int
    {
        $this->roll = rand(1, $this->faces);
        return $this->roll;
    }


    public function getLastRoll(): int
    {
        return $this->roll;
    }
}
