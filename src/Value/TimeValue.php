<?php

namespace Wikidata\Value;

use Wikidata\AbstractNode;

// use Carbon\Carbon;

class TimeValue extends AbstractNode
{
    protected $time;

    protected $timezone;

    protected $before;

    protected $after;

    protected $precision;

    protected $calendarmodel;

    /**
    * Class constructor.
    *
    * @param object $value StdClass object with time value
    */
    public function __construct($value)
    {
        $this->time     = $value->time;
        $this->timezone = $value->timezone;

        $this->before = $value->before;
        $this->after  = $value->after;

        $this->precision     = $value->precision;
        $this->calendarmodel = $value->calendarmodel;
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
        // return (new Carbon($this->time, $this->timezone))->format('Y-m-d h:i:s');
        return [
            'time'          => $this->time,
            'timezone'      => $this->timezone,
            'before'        => $this->before,
            'after'         => $this->after,
            'precision'     => $this->precision,
            'calendarmodel' => $this->calendarmodel,
        ];
    }
}
