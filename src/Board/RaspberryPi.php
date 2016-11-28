<?php
/**
 * Created by IntelliJ IDEA.
 * User: adame
 * Date: 11/27/16
 * Time: 4:36 PM
 */

namespace IoT\PPPO\Board;


use IoT\PPPO\Core\Board;
use IoT\PPPO\Core\Pin;

class RaspberryPi implements Board
{
    public function init(): void
    {
        // TODO: Implement init() method.
    }

    public function onReady(callable $callable): void
    {
        // TODO: Implement onReady() method.
    }

    public function getPin(string $block, int $pin): Pin
    {
        // TODO: Implement getPin() method.
    }
}