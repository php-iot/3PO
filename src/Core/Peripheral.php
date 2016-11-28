<?php
/**
 * Created by IntelliJ IDEA.
 * User: adame
 * Date: 11/27/16
 * Time: 7:05 PM
 */

namespace IoT\PPPO\Core;


interface Peripheral
{
    public function isEnabled() : bool;
}