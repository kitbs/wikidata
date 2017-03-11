<?php

namespace Wikidata\Search;

use Wikidata\AbstractNode;

class SearchItemMatch extends AbstractNode
{
    protected $type;

    protected $language;

    protected $text;

    /**
     * Class constructor.
     *
     * @param object $match StdClass object with search item match
     */
    public function __construct($match)
    {
        $this->type = $match->type;
        $this->language = $match->language;
        $this->text = $match->text;
    }

    public function jsonSerialize()
    {
        return [
            'type'     => $this->type,
            'language' => $this->language,
            'text'     => $this->text,
        ];
    }
}
