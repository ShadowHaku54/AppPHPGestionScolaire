<?php

function debug(...$vars): void
{
    static $compteur = 1;

    foreach ($vars as $var) {
        echo "<pre>";
        echo "------ DEBUG #{$compteur} ------\n";
        var_dump($var);
        echo "</pre>";
        $compteur++;
    }
}