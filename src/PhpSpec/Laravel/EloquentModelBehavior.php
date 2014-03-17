<?php namespace PhpSpec\Laravel;

use PhpSpec\Exception\Fracture;

/**
 * This behaviour provides extra matchers and workarounds for testing Eloquent
 * models.
 */
class EloquentModelBehavior extends LaravelObjectBehavior {

    /**
     * Wrapper to prevent unwanted overloading.
     *
     * @param  string $className
     * @param  array  $arguments
     * @return void
     */
    public function beAnInstanceOf($className, array $arguments = array())
    {
        $this->object->beAnInstanceOf($className, $arguments);
    }

    /**
     * Wrapper to prevent unwanted overloading.
     *
     * @param mixed $args
     * @return void
     */
    public function beConstructedWith()
    {
        call_user_func_array(array($this->object, 'beConstructedWith'), func_get_args());
    }

    /**
     * Register custom Eloquent matchers.
     *
     * @return array
     */
    public function getMatchers()
    {
        return array(
            new \PhpSpec\Laravel\Matcher\Eloquent\DefineRelationshipMatcher($this->presenter)
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
        $this->getWrappedObject();

        try {
            return parent::__call($method, $arguments);
        } catch (\BadMethodCallException $e) {
            throw new Fracture\MethodNotFoundException(
                sprintf('Method %s not found.', get_class($this->getWrappedObject()) . '::' . $method), $this->getWrappedObject(), $method, $arguments
            );
        }
    }
}
