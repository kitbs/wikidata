<?php

namespace Wikidata\Property;

use Wikidata\AbstractNode;

class PropertyReference extends AbstractNode
{
    protected $hash;

    protected $snaks;

    protected $snaksOrder;

    /**
    * Class constructor.
    *
    * @param object $reference StdClass object with reference
    */
    public function __construct($reference)
    {
        $this->hash = $reference->hash;
        $this->snaks = array_map([$this, 'createPropertyReferenceSnaks'], (array) $reference->snaks);
        $this->snaksOrder = $reference->{'snaks-order'};
    }

    /**
    * Creating list of property snaks.
    *
    * @param array $snaks Property snaks
    *
    * @return array List of /Property/PropertySnak
    */
    private function createPropertyReferenceSnaks($snaks)
    {
        return array_map([$this, 'createPropertySnak'], $snaks);
    }

    /**
    * Creating property snak object.
    *
    * @param object $snak StdClass object
    *
    * @return object /Property/PropertySnak
    */
    private function createPropertySnak($snak)
    {
        return new PropertySnak($snak);
    }

    public function getValue()
    {
        return $this->snaks;
    }

    public function jsonSerialize()
    {
        return [
            'hash' => $this->hash,
            'value' => collect($this->snaks)->transform(function($values) {
                return collect($values)->transform(function($value) {
                    return $value->jsonSerialize();
                });
            })->jsonSerialize(),
        ];
    }
}
