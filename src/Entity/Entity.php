<?php

namespace Wikidata\Entity;

use Wikidata\AbstractNode;
use Wikidata\Value\WikibaseItem;

class Entity extends AbstractNode
{
    private $entity = [];

    private $aliases = [];

    private $labels = [];

    private $descriptions = [];

    private $properties = [];

    /**
     * Class constructor.
     *
     * @param \Response\EntityResponse $entity
     */
    public function __construct($entity)
    {
        $this->entity['page_id'] = (isset($entity->pageid)) ? $entity->pageid : null;
        $this->entity['ns'] = (isset($entity->ns)) ? $entity->ns : null;
        $this->entity['title'] = (isset($entity->title)) ? $entity->title : null;
        $this->entity['lastrevid'] = (isset($entity->lastrevid)) ? $entity->lastrevid : null;
        $this->entity['modified'] = (isset($entity->modified)) ? $entity->modified : null;
        $this->entity['id'] = (isset($entity->id)) ? $entity->id : null;
        $this->entity['type'] = (isset($entity->type)) ? $entity->type : null;
        $this->aliases = (isset($entity->aliases)) ? array_map([$this, 'createEntityAlias'], (array) $entity->aliases) : null;
        $this->labels = (isset($entity->labels)) ? array_map([$this, 'createEntityValue'], (array) $entity->labels) : null;
        $this->descriptions = (isset($entity->descriptions)) ? array_map([$this, 'createEntityValue'], (array) $entity->descriptions) : null;
        $this->properties = (isset($entity->claims)) ? array_map([$this, 'createEntityProperties'], (array) $entity->claims) : null;
    }

    /**
     * Creating list of objects with properties.
     *
     * @param array $properties List properties
     *
     * @return array List of /Entity/EntityProperty
     */
    private function createEntityProperties($properties)
    {
        return array_map([$this, 'createEntityProperty'], (array) $properties);
    }

    /**
     * Creating entity property object.
     *
     * @param StdClass object $property Entity property
     *
     * @return object /Entity/EntityProperty
     */
    private function createEntityProperty($property)
    {
        return new EntityProperty($property);
    }

    /**
     * Creating list of objects with entity aliases.
     *
     * @param array $alias List entity aliases
     *
     * @return array List of /Entity/EntityAliases
     */
    private function createEntityAlias($alias)
    {
        return array_map([$this, 'createEntityValue'], $alias);
    }

    /**
     * Creating entity value object.
     *
     * @param StdClass object $value Entity alias
     *
     * @return object /Entity/EntityValue
     */
    private function createEntityValue($value)
    {
        return new EntityValue($value);
    }

    public function getEntityId()
    {
        return $this->entity['id'];
    }

    /**
     * Get entity property value by id and language.
     *
     * @param string $id   See more at https://www.wikidata.org/wiki/Wikidata:List_of_properties
     * @param string $lang Language of property's value
     *
     * @return mix Return list all property values or null if property not exist
     */
    public function getPropertyValues($id, $lang = 'en')
    {
        if (!$properties = $this->getProperty($id)) {
            return;
        }

        $output = [];

        foreach ($properties as $property) {
            $output[] = $property->getMainsnak()->getDatavalue()->getValue($lang);
        }

        return $output;
    }

    /**
     * Get property of entity by id.
     *
     * @param string $id See more at https://www.wikidata.org/wiki/Wikidata:List_of_properties
     *
     * @return mix Return list of /Entity/EntityProperty or null if property not exist
     */
    public function getProperty($id)
    {
        $id = strtoupper($id);

        if (!isset($this->properties[$id])) {
            return;
        }

        return $this->properties[$id];
    }

    /**
     * Get all properties of entity.
     *
     * @return mix Return list of /Entity/EntityProperty or null if property not exist
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Get property values with qualifier as array.
     *
     * @param string $prop_id Property id. See more at https://www.wikidata.org/wiki/Wikidata:List_of_properties
     * @param string $qual_id Qualifier id. See more at https://www.wikidata.org/wiki/Wikidata:List_of_properties
     * @param string $lang    Language
     *
     * @return mix Return array where key - property value, value - qualifier value or null
     */
    public function getPropertyValuesWithQualifierAsArray($prop_id, $qual_id, $lang = 'en')
    {
        if (!$properties = $this->getProperty($prop_id)) {
            return;
        }

        $output = [];

        foreach ($properties as $property) {
            $propValue = $property->getMainsnak()->getDatavalue()->getValue($lang);

            $output[$propValue] = $property->getQualifierValues($qual_id)[0];
        }

        return $output;
    }

    /**
     * Get entity alias value by language.
     *
     * @param string $lang Language of alias value
     *
     * @return mix Return list all alias values or null if alias not exist
     */
    public function getAliasValues($lang = 'en')
    {
        if (!isset($this->aliases[$lang])) {
            return;
        }

        $output = [];

        foreach ($this->aliases[$lang] as $alias) {
            $output[] = $alias->getValue();
        }

        return $output;
    }

    /**
     * Get entity label.
     *
     * @param string $lang Language of entity's label
     *
     * @return mix Return string with value or null
     */
    public function getLabel($lang = 'en')
    {
        if (!isset($this->labels[$lang])) {
            return;
        }

        return $this->labels[$lang]->getValue();
    }

    /**
     * Get entity description.
     *
     * @param string $lang Language of entity's description
     *
     * @return mix
     */
    public function getDescription($lang = 'en')
    {
        if (!isset($this->descriptions[$lang])) {
            return;
        }

        return $this->descriptions[$lang]->getValue();
    }

    public function getLabels()
    {
        return $this->labels;
    }

    public function getDescriptions()
    {
        return $this->descriptions;
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function gatherEntityIds()
    {
        $ids = [
            $this->getEntityId(),
        ];

        foreach ($this->properties as $id => $properties) {
            foreach ($properties as $property) {
                $snak = $property->getMainsnak()->getDatavalue()->getDatavalue();

                if ($snak instanceof WikibaseItem) {
                    $ids[] = $snak->getEntityId();
                }

                foreach ($property->getQualifiers() as $qualifiers) {
                    foreach ($qualifiers as $qualifier) {
                        $snak = $qualifier->getDatavalue()->getDatavalue();

                        if ($snak instanceof WikibaseItem) {
                            $ids[] = $snak->getEntityId();
                        }
                    }
                }

                foreach ($property->getReferences() as $propertyReferences) {
                    foreach ($propertyReferences->getValue() as $references) {
                        foreach ($references as $reference) {
                            $snak = $reference->getDatavalue()->getDatavalue();

                            if ($snak instanceof WikibaseItem) {
                                $ids[] = $snak->getEntityId();
                            }
                        }
                    }
                }
            }
        }

        sort($ids);

        return array_values(array_unique($ids));
    }

    public function gatherPropertyIds()
    {
        $ids = [];

        foreach ($this->properties as $id => $properties) {
            $ids[] = $id;

            foreach ($properties as $property) {
                foreach ($property->getQualifiers() as $qualifiers) {
                    foreach ($qualifiers as $qualifier) {
                        $ids[] = $qualifier->getProperty();
                    }
                }

                foreach ($property->getReferences() as $propertyReferences) {
                    foreach ($propertyReferences->getValue() as $references) {
                        foreach ($references as $reference) {
                            $ids[] = $reference->getProperty();
                        }
                    }
                }
            }
        }

        sort($ids);

        return array_values(array_unique($ids));
    }

    public function jsonSerialize()
    {
        return [
            'id'           => $this->getEntityId(),
            'labels'       => collect($this->getLabels())->jsonSerialize(),
            'descriptions' => collect($this->getDescriptions())->jsonSerialize(),
            'aliases'      => collect($this->getAliases())->transform(function ($aliases) {
                return collect($aliases)->transform(function ($alias) {
                    return $alias->jsonSerialize();
                });
            })->jsonSerialize(),
            'properties'   => collect($this->getProperties())->transform(function ($properties) {
                return collect($properties)->transform(function ($property) {
                    return $property->jsonSerialize();
                });
            })->jsonSerialize(),
        ];
    }
}
