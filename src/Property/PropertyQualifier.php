<?php

namespace Wikidata\Property;

use Wikidata\AbstractNode;

class PropertyQualifier extends AbstractNode
{
    protected $hash;

    protected $snaktype;

    protected $property;

    protected $datatype;

    protected $datavalue;

    /**
     * Class constructor.
     *
     * @param object $qualifier StdClass object with qualifier
     */
    public function __construct($qualifier)
    {
        $this->hash = $qualifier->hash;
        $this->snaktype = $qualifier->snaktype;
        $this->property = $qualifier->property;
        $this->datatype = $qualifier->datatype;

        if ($this->snaktype === 'novalue') {
            $this->datavalue = new PropertyDatavalue('novalue');
        } elseif (!isset($qualifier->datavalue)) {
            $this->datavalue = new NullPropertyDatavalue();
        } else {
            $this->datavalue = new PropertyDatavalue($qualifier->datavalue);
        }
    }

    /**
     * Get property datavalue.
     *
     * @return object /Property/PropertyDatavalue
     */
    public function getDatavalue()
    {
        return $this->datavalue;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function jsonSerialize()
    {
        return [
            // 'hash'  => $this->hash,
            'type'  => $this->datatype,
            'value' => $this->getDatavalue()->jsonSerialize(),
        ];
    }
}
