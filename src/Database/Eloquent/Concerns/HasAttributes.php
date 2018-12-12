<?php

namespace Awobaz\Mutator\Database\Eloquent\Concerns;

use Awobaz\Mutator\Mutator;

trait HasAttributes
{
    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        // If the attribute has custom getters, we will call them
        if(property_exists($this, 'getters') && isset($this->getters[$key])) {
            $value = $this->getAttributeFromArray($key);

            $getters = array_wrap($this->getters[$key]);
            foreach($getters as $getter){
                $value = Mutator::getter($getter)($this, $value, $key);
            }

            return $value;
        }

        return parent::getAttributeValue($key);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        // If the attribute has custom setters, we will call them
        if(property_exists($this, 'setters') && isset($this->setters[$key])) {
            $setters = array_wrap($this->setters[$key]);
            foreach($setters as $setter){
                $this->attributes[$key] = Mutator::setter($setter)($this, $value, $key);
            }

            return $this->attributes[$key];
        }

        return parent::setAttribute($key, $value);
    }
}