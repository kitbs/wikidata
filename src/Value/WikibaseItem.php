<?php

namespace Wikidata\Value;

use Wikidata\AbstractNode;

use Wikidata\Wikidata;

class WikibaseItem extends AbstractNode
{
    protected $entityType;

    protected $numericId;

    /**
    * Class constructor.
    *
    * @param object $value StdClass object with wikibase item
    */
    public function __construct($value)
    {
        $this->entityType = $value->{'entity-type'};
        $this->numericId  = $value->{'numeric-id'};
    }

    public function getEntityType()
    {
        return $this->entityType;
    }

    public function getNumericId()
    {
        return $this->numericId;
    }

    public function getEntityId()
    {
        return sprintf('Q%s', $this->getNumericId());
    }

    /**
    * Call wikidata api and get only label of wikibase item.
    *
    * @return string $value
    */
    public function getValue($lang)
    {
        $wikidata = new Wikidata();

        return $wikidata->property($this->getEntityId(), $lang)->getLabel();
    }

    public function jsonSerialize()
    {
        return $this->getEntityId();
    }
}
