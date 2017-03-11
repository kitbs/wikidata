<?php

namespace Wikidata\Property;

use Wikidata\AbstractNode;

class NullPropertyDatavalue extends AbstractNode
{
    public function getValue()
    {
        return null;
    }

    public function getDatavalue()
    {
        return $this->getValue();
    }

    public function jsonSerialize()
    {
        return $this->getValue();
    }
}
