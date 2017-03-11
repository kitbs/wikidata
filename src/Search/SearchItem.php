<?php

namespace Wikidata\Search;

use Wikidata\AbstractNode;

class SearchItem extends AbstractNode
{
    private $id;

    private $url;

    private $label;

    private $description;

    private $match;

    private $aliases;

    /**
    * Class constructor.
    *
    * @param object $item StdClass object with item
    */
    public function __construct($item)
    {
        $this->id = $item->id;
        $this->url = $item->url;
        $this->label = $item->label;
        $this->description = (isset($item->description)) ? $item->description : null;
        $this->match = new SearchItemMatch($item->match);
        $this->aliases = (isset($item->aliases)) ? $item->aliases : null;
    }

    /**
    * Get only entity id.
    *
    * @return string
    */
    public function getEntityId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function jsonSerialize()
    {
        return [
            'id'          => $this->getEntityId(),
            'url'         => $this->url,
            'label'       => $this->label->jsonSerialize(),
            'description' => $this->description instanceof AbstractNode ? $this->description->jsonSerialize() : null,
            'aliases'     => collect($this->aliases)->transform(function($aliases) {
                return collect($aliases)->transform(function($alias) {
                    return $alias->jsonSerialize();
                });
            })->jsonSerialize(),
            'match' => $this->match instanceof AbstractNode ? $this->match->jsonSerialize() : null,
        ];
    }
}
