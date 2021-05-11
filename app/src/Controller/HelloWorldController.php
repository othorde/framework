<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Highscore;
use App\Dice\YatzyGame;

class HelloWorldController extends AbstractController
{
    /**
     * @Route("/")
    */

    public function index(): Response
    {
        session_start();
        return $this->render('articles/index.html.twig', [
            'message' => "Home",
            'title' => "Home",
        ]);
    }

    /**
     * @Route("/hello")
    */
    public function hello(): Response
    {

        return $this->render('base.html.twig', [
            'message' => "Hello World as controller annotation",
            'title' => "Hello",

        ]);
    }

    /**
     * @Route("/highscore", name="highscore")
     * @Method({"GET", "POST"})
     */
    public function highscore(): Response
    {
        $highscore = $this->getDoctrine()
            ->getRepository(Highscore::class)
            ->findAll();

        if (!$highscore) {
            throw $this->createNotFoundException(
                'No book found for '
            );
        }

        //return new Response('Check out this great book: ');

        // or render a template
        // in the template, print things with {{ book.name }}
        return $this->render('highscore.html.twig', ['highscore' => $highscore]);
    }

    /**
     * @Route("/createHighscore")
     * @Method({"GET", "POST"})
    */
    public function createHighscore(): Response
    {
        var_dump($_POST);

        if (isset($_POST["playername"])) {
            $entityManager = $this->getDoctrine()->getManager();
            $highscore = new highscore();
            $highscore->setName($_POST["playername"]);
            $highscore->setScore($_POST["highscore"]);
            $highscore->setDate(new \DateTime());
            $entityManager->persist($highscore);
            $entityManager->flush();
        }

        return $this->redirectToRoute('highscore', [
            'highscore' => $highscore
        ]);
    }

    /**
     * @Route("/yatzy")
     * @Method({"GET", "POST"})
    */
    public function yatzy(): Response
    {
        if (empty($_SESSION)) {
            session_start();
            $nrOfDice = 5;
            $game = new YatzyGame($nrOfDice);
            $_SESSION["yatzygame"] = $game;
        }

        if (!isset($_SESSION["throws"])) {
            $_SESSION["throws"] = 1;
            $_SESSION["round"] = 1;
        } else if (isset($_SESSION["throws"])) {
            $_SESSION["throws"] += 1;
            if ($_SESSION["throws"] == 4) {
                $_SESSION["throws"] = 1;
                $_SESSION["round"] += 1;
            }
        }
        ?>
        <?php



        if (empty($_POST)) {
            $nrOfDie2ThrowNext = 5;
        } else {
            $nrOfDie2ThrowNext = 6 - count($_POST); // 6 = 5 tärningar i arrayn plus "KASTA" därav 6
        }
        $game = $_SESSION["yatzygame"]; //hämtar obj
        $game->roll(); // roll
        $game->setNrOfDice($nrOfDie2ThrowNext);
        $whatRound = $game->whatRound();
        $whatThrow = $game->whatThrow();
        $newRoundOrNot = $game->newRoundOrNot();
        $graphDice = $game->getGraphicalDices(); // hämtar värden skriver ut
        $getLastRoll = $game->getLastRollWithoutSum();
        $savedDicesGraphical = $game->savedDices();
        $returnMess = $game->returnMess();
        $checkScore = $game->checkScore();
        $checkSum = $game->getSum();
        ?>
        <br>
        <p> Poäng denna runda: <?= $checkScore ?> </p>
        <p> Summa: <?= $checkSum ?> </p>
        <p> Du har kastat  <?= $whatThrow ?> kast denna runda. </p>
        <p> Du är på omgång nummer <?= $whatRound ?>. </p>
        <p><?= $returnMess ?> </p>

        <?php
        if ($whatRound > 6) {
            ?>
            <form action=createHighscore method="POST">
            <p> Summa: <?= $checkSum ?> </p>

            <input type="text" id="player" name= "playername"> <br>
            <input type="text" name= "highscore" value= <?= $checkSum ?> readonly>

            <input type="submit" name= "" value= "Save highscore">
            </form>
            <?php
            $_SESSION["throws"] = 0;
            $_SESSION["round"] = 0;
        }
        ?>



        <p class = "mark-newround"> <?= $newRoundOrNot ?> </p>
        <!-- Grafisk visning av tärningarna -->
        <p class = "dice-utf8">
            <?php if (isset($graphDice)) {
                foreach ($graphDice as $value) : ?>
                    <i class="<?= $value ?>"></i>
                <?php endforeach;
            }
            ?>
        </p>
        <hr>
        <p>Välj nedan vilka tärningar du vill spara </p>
        <!-- checkboxes för tärningar -->
        <hr>
         <form action=yatzy method="POST">
            <?php if (isset($getLastRoll)) {
                $nameOfValue = 0;
                foreach ($getLastRoll as $value) :
                    $nameOfValue = $nameOfValue + 1?> <!-- för att byta namn på värdet -->
                    <input type="checkbox" name= <?= $nameOfValue ?> value = <?= $value ?>></i>
                <?php endforeach;
            }
            ?>
        <!-- Grafisk visning av sparade tärningarna -->
        <p>Sparade tärningar </p>
        <p class = "dice-utf8"> 
            <?php foreach ($savedDicesGraphical as $value) : ?>
                    <i class="<?= $value ?>"></i>
                    <?php
                    $nameOfValue = $nameOfValue + 1 ?><!-- för att byta namn på värdet , kan ej ha samma namn -->
            <?php endforeach;
                $savedDicesGraphical ?>
        </p>
        <!-- Checkboxes sparade tärningarna -->
        <?php
            $nameOfValue = 0;
        foreach ($_SESSION["savedDices"] as $value) :
            $nameOfValue = $nameOfValue - 1?> <!-- för att byta namn på värdet, kan ej ha samma namn -->
            <input type="checkbox" name= <?= $nameOfValue ?> value = <?= $value ?>></i>
        <?php endforeach; ?>
        <input type="submit" name="kasta" value = "KASTA">
        
        </form>
        <?php
        return $this->render('yatzy.html.twig', [
            'message' => "Hello yatzy",
        ]);
    }
}


