<?php

namespace Wikidata\Value;

use Wikidata\AbstractNode;

class GlobeCoordinateValue extends AbstractNode
{
    protected $latitude;

    protected $longitude;

    protected $altitude;

    protected $precision;

    protected $globe;

    /**
    * Class constructor.
    *
    * @param object $value StdClass object with globe coordinate value
    */
    public function __construct($value)
    {
        $this->latitude  = $value->latitude;
        $this->longitude = $value->longitude;
        $this->altitude  = $value->altitude;
        $this->precision = $value->precision;
        $this->globe     = $value->globe;
    }

    /**
    * Get value.
    *
    * @return object Return object with all data
    *
    * TODO: prepare value to display
    */
    public function getValue()
    {
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'altitude'  => $this->altitude,
            'precision' => $this->precision,
            'globe'     => $this->globe,
        ];
    }
}
