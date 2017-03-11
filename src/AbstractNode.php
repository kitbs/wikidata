<?php

namespace Wikidata;

use JsonSerializable;

abstract class AbstractNode implements JsonSerializable
{
    public function toArray()
    {
        return $this->jsonSerialize();
    }
}
