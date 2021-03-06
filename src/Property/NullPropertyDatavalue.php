<?php

namespace Wikidata\Property;

use Wikidata\AbstractNode;

class NullPropertyDatavalue extends AbstractNode
{
    public function getValue()
    {
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
