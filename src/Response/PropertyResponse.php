<?php

namespace Wikidata\Response;

use Wikidata\AbstractNode;

use Wikidata\Entity\Entity;

class PropertyResponse extends AbstractNode
{
    private $properties;

    private $success;

    /**
     * Class constructor.
     *
     * @param json   $data Wikidata json response with property
     * @param string $lang Language
     */
    public function __construct($data, $lang)
    {
        $response = json_decode($data);

        $this->properties = array_map([$this, 'createEntity'], (array) $response->entities);
        $this->success = $response->success;
        $this->lang = $lang;
    }

    /**
     * Get first property.
     *
     * @return object /Entity/Entity
     */
    public function first()
    {
        $properties = array_values($this->properties);

        return $properties[0];
    }

    /**
     * Get all entity or only single by id.
     *
     * @param int $id Entity id (like Q26) or null
     *
     * @return mix Return array with /Entity/Entity or single /Entity/Entity
     */
    public function get($id = null)
    {
        if ($id) {
            return $this->properties[$id];
        }

        return $this->properties;
    }

    /**
     * Creating entity.
     *
     * @param object $item StdClass object with property
     *
     * @return object /Entity/Entity
     */
    private function createEntity($item)
    {
        return new Entity($item);
    }

    /**
     * Get label of property.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->first()->getLabel($this->lang);
    }

    public function jsonSerialize()
    {
        return collect($this->properties)->jsonSerialize();
    }
}
