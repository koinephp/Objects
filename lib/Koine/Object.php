<?php

namespace Koine;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
abstract class Object
{
    /**
     * Get the class name of the object
     * @return string
     */
    public function getClass()
    {
        return get_class($this);
    }

    /**
     * Object to string
     * @return string
     */
    public function toString()
    {
        return (string) $this;
    }

    /**
     * Alias to missingMethod()
     *
     * @see Koine\Object::methodMissing()
     * @throws Koine\NoMethodException
     */
    public function __call($method, $args)
    {
        return $this->methodMissing($method, $args);
    }

    /**
     * Method missing callback
     *
     * @throws Koine\NoMethodException
     */
    public function methodMissing($method, $args)
    {
        return $this->send($method, $args);
    }

    /**
     * Dinamicaly calls method
     * @see Koine\Object::__send
     * @return mixed
     * @throws Koine\NoMethodError
     */
    public function send()
    {
        return call_user_func_array(array($this, '__send'), func_get_args());
    }

    /**
     * Dinamicaly calls method
     * @return mixed
     * @throws Koine\NoMethodError
     */
    final public function __send()
    {
        $args   = func_get_args();
        $method = array_shift($args);

        if ($this->__respondTo($method)) {
            return call_user_func_array(array($this, $method), $args);
        }

        $message = new String("Undefined method '");
        $message->append($method)->append("' for ")->append($this->getClass());
        throw new NoMethodException($message);
    }

    /**
     * Informs if the given method exists
     * @return boolean
     */
    final public function __respondTo($method)
    {
        return $this->getMethods()->hasValue($method);
    }

    /**
     * Informs if the given method exists
     * @return boolean
     */
    public function respondTo($method)
    {
        return $this->__respondTo($method);
    }

    /**
     * Get the methods that the object responds to
     * @see Koine\Object::__getMethods()
     *
     * @return Koine\Hash.
     */
    public function getMethods()
    {
        return $this->__getMethods();
    }

    /**
     * Get the methods that the object responds to
     *
     * @return Koine\Hash.
     */
    final public function __getMethods()
    {
        $methods = get_class_methods($this);
        $hash = new Hash($methods);

        return $hash;
    }
}
