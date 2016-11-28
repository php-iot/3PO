<?php
/**
 * Created by IntelliJ IDEA.
 * User: adame
 * Date: 11/27/16
 * Time: 4:20 PM
 */

namespace IoT\PPPO\Core;


interface Board
{
    public function init(): void;
    public function getPin(int $pin, string $block = null): Pin;
}