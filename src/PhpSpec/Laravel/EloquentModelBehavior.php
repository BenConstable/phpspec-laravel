<?php namespace PhpSpec\Laravel;

use PhpSpec\Exception\Fracture;

/**
 * This behaviour provides extra matchers and workarounds for testing Eloquent
 * models.
 */
class EloquentModelBehavior extends LaravelObjectBehavior {

    /**
     * Register custom Eloquent matchers.
     *
     * @return array
     */
    public function getMatchers()
    {
        return array(
            new \PhpSpec\Laravel\Matcher\Eloquent\DefineRelationshipMatcher
        );
    }

    /**
     * We override __call to throw a Fracture Exception for missing methods,
     * which allows PHPSpec to suggest method creation.
     *
     * If we don't do this, the call will error on the QueryBuilder,
     * which we don't really want.
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($method, array $arguments = array())
    {
        try {
            return parent::__call($method, $arguments);
        } catch (\BadMethodCallException $e) {
            throw new Fracture\MethodNotFoundException(
                sprintf('Method %s not found.', get_class($this->getWrappedObject()) . '::' . $method), $this->getWrappedObject(), $method, $arguments
            );
        }
    }
}
