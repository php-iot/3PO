<?php
use IoT\PPPO\Pin\GPIO;

/**
 * Created by IntelliJ IDEA.
 * User: adame
 * Date: 11/27/16
 * Time: 6:53 PM
 */
class LED implements \IoT\PPPO\Core\Peripheral
{
    private $pin;


    /**
     * LED constructor.
     * @param GPIO $pin
     */
    public function __construct(GPIO $pin)
    {
        $this->pin = $pin;
        \Amp\wait($this->pin->initialize(GPIO::DIRECTION_OUT));
        \Amp\wait($this->off());
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function on() : Generator {
        yield from $this->pin->switch(GPIO::STATE_ON);
    }

    public function off() : Generator {
        yield from $this->pin->switch(GPIO::STATE_OFF);
    }

    public function toggle() : Generator {
        yield from $this->pin->switch($this->pin->isOn() ? GPIO::STATE_OFF : GPIO::STATE_ON);
    }

    public function isOn() : bool {
        return $this->pin->isOn();
    }
}