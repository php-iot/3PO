<?php
/**
 * Created by IntelliJ IDEA.
 * User: adame
 * Date: 11/27/16
 * Time: 4:41 PM
 */

namespace IoT\PPPO\Core;


interface Device
{
    public function getMake() : string;

    public function getModel() : string;

    public function getSerialNumber() : string;
}