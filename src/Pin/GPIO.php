<?php
namespace IoT\PPPO\Pin;

use Amp\File\Driver;
use Amp\File\Handle;
use Amp\Promise;
use IoT\PPPO\Core\Pin;

/**
 * Created by IntelliJ IDEA.
 * User: adame
 * Date: 11/27/16
 * Time: 4:44 PM
 */
class GPIO implements Pin
{
    const DIRECTION_IN = 'in';
    const DIRECTION_OUT = 'out';
    const DIRECTIONS = [self::DIRECTION_IN, self::DIRECTION_OUT];

    const EDGE_NONE = 'none';
    const EDGE_RISING = 'rising';
    const EDGE_FALLING = 'falling';
    const EDGE_BOTH = 'both';
    const EDGES = [self::EDGE_NONE, self::EDGE_RISING, self::EDGE_FALLING, self::EDGE_BOTH];

    const STATE_OFF = 0;
    const STATE_ON = 1;
    const STATES = [self::STATE_OFF, self::STATE_ON];

    /**
     * @var bool
     */
    private $writeable;

    /**
     * @var int
     */
    private $pin;

    /**
     * @var Driver
     */
    private $driver;

    /**
     * @var Handle
     */
    private $handle;

    public function __construct(Driver $driver, int $pin) {
        $this->pin = $pin;
        $this->driver = $driver;
    }

    public function read(): Promise
    {
        $this->getHandle()->seek(0);
        return $this->getHandle()->read(1);
    }

    /**
     * @param string $data
     * @throw LogicException when trying to write to pin which is not writeable or unavailable
     * @return Promise
     */
    public function write(string $data): Promise
    {
        return $this->getHandle()->write($data);
    }

    public function switch(int $state) : Promise {
        if (!in_array($state, self::STATES)) {
            throw new \InvalidArgumentException(sprintf(
                "Parameter state must be one of: %s",
                implode(", ", self::STATES)
            ));
        }
        return $this->write(strval($state));
    }

    public function isOn() : \Generator {
        $value = yield $this->read();
        return intval($value) == self::STATE_ON;
    }


    public function initialize(
        string $direction = self::DIRECTION_OUT,
        string $edge = self::EDGE_BOTH,
        bool $activeLow = false
    ) : \Generator {
        if (!in_array($direction, self::DIRECTIONS)) {
            throw new \InvalidArgumentException(sprintf(
                "Parameter direction must be one of: %s",
                implode(", ", self::DIRECTIONS)
            ));
        }

        if (!in_array($edge, self::EDGES)) {
            throw new \InvalidArgumentException(sprintf(
                "Parameter edge must be one of: %s",
                implode(", ", self::EDGES)
            ));
        }

        $root = "/sys/class/gpio/gpio{$this->pin}";
        $pinExists = yield $this->driver->exists($root);
        if (!$pinExists) {
            yield (yield $this->driver->open("/sys/class/gpio/export", "w"))->write($this->pin);
        }
        yield (yield $this->driver->open("{$root}/direction", "w"))->write($direction);
        if ($direction == self::DIRECTION_IN) {
            yield (yield from $this->driver->open("{$root}/edge", "w"))->write($edge);
            yield (yield from $this->driver->open("{$root}/active_low", "w"))->write($activeLow);
            $this->writeable = false;
        } else {
            $this->writeable = true;
        }
        $this->handle = yield $this->driver->open("{$root}/value", "rw");
    }

    /**
     * @return Handle
     */
    private function getHandle(): Handle
    {
        if (!($this->handle instanceof Handle)) {
            throw new \LogicException("Pin has not been initialized");
        }
        return $this->handle;
    }
}