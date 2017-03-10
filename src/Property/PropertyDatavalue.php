<?php

namespace Wikidata\Property;

use Wikidata\Value\GlobeCoordinateValue;
use Wikidata\Value\QuantityValue;
use Wikidata\Value\TimeValue;
use Wikidata\Value\WikibaseItem;

class PropertyDatavalue
{
    /**
     * Class constructor.
     *
     * @param object $datavalue StdClass object with datavalue
     */
    public function __construct($datavalue)
    {
        if ($datavalue == 'novalue') {
            $this->value = $this->createPropertyValueByType(null, 'novalue');
            $this->type = 'novalue';

            return;
        }

        $this->value = $this->createPropertyValueByType($datavalue->value, $datavalue->type);
        $this->type = $datavalue->type;
    }

    /**
     * Creating property value by type.
     *
     * @param mix    $value
     * @param string $type  Value type
     *
     * @return mix Return object or string
     */
    private function createPropertyValueByType($value, $type)
    {
        switch ($type) {

            case 'wikibase-entityid':
                return new WikibaseItem($value);
            case 'time':
                return new TimeValue($value);
            case 'quantity':
                return new QuantityValue($value);
            case 'globecoordinate':
                return new GlobeCoordinateValue($value);
            default:
                return $value;

        }
    }

    /**
     * Get only value without type.
     *
     * @param string $lang Language
     *
     * @return mix Return string or array of objects
     */
    public function getValue($lang = 'en')
    {
        if (is_null($this->value)) {
            return;
        }

        if (is_string($this->value)) {
            return $this->value;
        }

        return $this->value->getValue($lang);
    }
}
