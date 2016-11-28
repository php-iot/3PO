<?php
/**
 * Created by IntelliJ IDEA.
 * User: adame
 * Date: 11/27/16
 * Time: 4:24 PM
 */

namespace IoT\PPPO\Core;


use Amp\Promise;

interface Pin
{
    public function read() : Promise;

    public function write(string $data) : Promise;
}