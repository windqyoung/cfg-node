<?php


namespace Wqy\Config;


class Node implements \JsonSerializable, \ArrayAccess, \Iterator
{
    private $value;
    private $parent;
    private $children = [];

    public function __construct($value = null, $parent = null)
    {
        $this->value = $value;
        $this->parent = $parent;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $name
     * @return \Wqy\Config\Node
     */
    public function __get($name)
    {
        if (isset($this->children[$name])) {
            return $this->children[$name];
        }
        return $this->children[$name] = new self(null, $this);
    }

    public function __set($name, $value)
    {
        if (isset($this->children[$name])) {
            $this->children[$name]->setValue($value);
        }
        else {
            $n = $this->children[$name] = new self($value, $this);
        }
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($name)
    {
        $segs = explode('.', $name);

        $obj = $this;
        foreach ($segs as $sg) {
            $obj = $obj->__get($sg);
        }

        return $obj->getValue();
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($name, $value)
    {
        $segs = explode('.', $name);

        $obj = $this;
        foreach ($segs as $sg) {
            $obj = $obj->__get($sg);
        }

        $obj->setValue($value);
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($name)
    {
        $this->offsetSet($name, null);
    }


    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($name)
    {
        return $this->offsetGet($name) !== null;
    }

    /**
     * {@inheritDoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return ['value' => $this->value, 'children' => $this->children];
    }
    /**
     * {@inheritDoc}
     * @see Iterator::current()
     */
    public function current()
    {
        return current($this->children);
    }

    /**
     * {@inheritDoc}
     * @see Iterator::key()
     */
    public function key()
    {
        return key($this->children);
    }

    /**
     * {@inheritDoc}
     * @see Iterator::next()
     */
    public function next()
    {
        next($this->children);
    }

    /**
     * {@inheritDoc}
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        reset($this->children);
    }

    /**
     * {@inheritDoc}
     * @see Iterator::valid()
     */
    public function valid()
    {
        return current($this->children) !== false;
    }

    public function foreachRecursive($callback, $ignoreNull = true)
    {
        $this->doForeachRecursive($callback, $ignoreNull);
    }

    private function doForeachRecursive($callback, $ignoreNull, $keys = [])
    {
        if (! $ignoreNull) {
            $callback($this, $keys);
        }
        else if ($this->value !== null) {
            $callback($this, $keys);
        }
        foreach ($this->children as $k => $v) {
            $tempKeys = $keys;
            $tempKeys[] = $k;
            $v->doForeachRecursive($callback, $ignoreNull, $tempKeys);
        }
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}
