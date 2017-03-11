<?php

namespace Wikidata\Property;

use Wikidata\AbstractNode;

class PropertySnak extends AbstractNode
{
    protected $snaktype;

    protected $property;

    protected $datatype;

    protected $datavalue;

    /**
    * Class constructor.
    *
    * @param object $snak StdClass object with snak
    */
    public function __construct($snak)
    {
        $this->snaktype = $snak->snaktype;
        $this->property = $snak->property;
        $this->datatype = $snak->datatype;
        $this->datavalue = ($snak->snaktype == 'value') ? new PropertyDatavalue($snak->datavalue) : new NullPropertyDatavalue();
    }

    /**
    * Get only datavalue of snak.
    *
    * @return object /Property/PropertyDatavalue
    */
    public function getDatavalue()
    {
        return $this->datavalue;
    }

    public function getDatatype()
    {
        return $this->datatype;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function jsonSerialize()
    {
        return [
            'type'  => $this->datatype,
            'value' => $this->getDatavalue()->jsonSerialize(),
        ];
    }
}
