<?php

namespace Fulll\Domain\Collection;

use Fulll\Domain\Collection\Attribute\CheckItemClass;
use Fulll\Domain\Collection\Exception\BadAttributeParameterException;
use Fulll\Domain\Collection\Exception\BadClassException;
use Fulll\Domain\Collection\Exception\ForbiddenUsageException;
use Fulll\Domain\Collection\Exception\InvalidKeyException;
use Fulll\Domain\Collection\Exception\OutOfBoundsException;
use Fulll\Domain\Collection\Exception\ValueCheckFailedException;

/** @phps  */
class Collection implements \ArrayAccess, \Countable, \Iterator, \JsonSerializable
{

    protected array $array = [];
    protected array $allowedClasses = [];

    public function __construct(
        array|Collection $array = [],
    ) {

        $this->allowedClasses = $this->getAllowedClasses();

        // Set values of array to new collection
        foreach ($array as $key => $item) {

            if ($key === null) {
                throw new \LogicException('null key !');
            }

            $this->set($key, $item);

        }

    }

    /**
     * @return string[]
     * @throws BadAttributeParameterException
     */
    private function getAllowedClasses(): array
    {

        $result = [];

        $reflection = new \ReflectionObject($this);
        $attributes = $reflection->getAttributes(CheckItemClass::class);
        foreach ($attributes as $attribute) {

            $classname = ($attribute->newInstance())
                ->classname;

            if (!class_exists($classname)) {
                throw new BadAttributeParameterException(
                    'The ' . CheckItemClass::class . ' attribute accept only existing class, ' . $classname .
                    ' given is not a class'
                );
            }

            $result[] = $classname;

        }

        return $result;

    }

    public function __clone(): void
    {

        foreach ($this as $key => $item) {
            if (is_object($item)) {
                $this[$key] = clone $item;
            }
        }

    }

    /**
     * Set value
     * @return $this
     */
    public function set(int|string $key, mixed $value): self
    {

        if (count($this->allowedClasses) > 0) {

            // Check allowed classes
            $check = false;
            foreach ($this->allowedClasses as $allowedClass) {

                if ($value instanceof $allowedClass) {
                    $check = true;
                    break;
                }

            }

            if (!$check) {
                throw new ValueCheckFailedException(
                    static::class . ' rejected value ' .
                    '(allowed : ' . implode(', ', $this->allowedClasses) . ')'
                );
            }

        }

        // If is array, transform to collection
        if (is_array($value)) {

            $this->array[$key] = new Collection($value);

            return $this;

        }

        // Set value
        $this->array[$key] = $value;

        return $this;

    }

    /**
     * Check if offset exists
     * @param int|string $offset
     */
    public function offsetExists(mixed $offset): bool
    {

        return array_key_exists($offset, $this->array);

    }

    public function exists(int|string $key): bool
    {

        return $this->offsetExists($key);

    }

    public function valueExists(mixed $value): bool
    {

        return in_array($value, $this->array);

    }

    /**
     * Get value of offset
     */
    public function offsetGet(mixed $offset): mixed
    {

        return $this->array[$offset];

    }

    /**
     * Set offset value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {

        if ($offset === null) {
            $offset = 0;
            foreach ($this->array as $key => $item) {
                if (is_int($key) && $key >= $offset) {
                    $offset = $key + 1;
                }
            }
        } else if (
            !is_int($offset) &&
            !is_string($offset)
        ) {
            throw new InvalidKeyException('Key must be integer or string');
        }

        // Set array with new value
        $this->set($offset, $value);

    }

    /**
     * Unset offset
     */
    public function offsetUnset(mixed $offset): void
    {

        unset($this->array[$offset]);

    }

    /**
     * Iterator rewind bridge
     */
    public function rewind(): void {
        reset($this->array);
    }

    /**
     * Iterator current bridge
     */
    public function current(): mixed {
        return current($this->array);
    }

    /**
     * Iterator key bridge
     */
    public function key(): int|string|null {
        return key($this->array);
    }

    public function keys(): static
    {

        /** @phpstan-ignore-next-line  */
        $result = new static();
        $this->rewind();
        while ($key = $this->key()) {
            $result[] = $key;
            $this->next();
        }

        return $result;

    }

    /**
     * Iterator next bridge
     */
    public function next(): void {
        next($this->array);
    }

    /**
     * Iterator valid bridge
     */
    public function valid(): bool {
        return key($this->array) !== null;
    }

    /**
     * Count items
     */
    public function count(): int
    {

        return count($this->array);

    }

    /**
     * Merge array or collection to collection
     * @param Collection|array $collection
     * @param bool $applyToThis
     * @return Collection
     */
    public function merge(Collection|array $collection, bool $applyToThis = false): Collection
    {

        /** @phpstan-ignore-next-line */
        $result = $applyToThis ? $this : new static($this);

        foreach ($collection as $key => $value) {

            if ($key === null) {
                throw new \LogicException('null key !');
            }

            if ($result->exists($key)) {
                $result[] = $value;
            } else {
                $result->set($key, $value);
            }
        }

        return $result;

    }

    /**
     * Fusion array or collection to collection by key
     * @param Collection|array $collection
     * @param bool $applyToThis
     * @return Collection
     */
    public function fusionByKey(Collection|array $collection, bool $applyToThis = false): Collection
    {

        /** @phpstan-ignore-next-line */
        $result = $applyToThis ? $this : new static($this);

        foreach ($collection as $key => $value) {
            /** @phpstan-ignore-next-line */
            if (!$result->exists($key)) {
                $result[$key] = $value;
            }
        }

        return $result;

    }

    /**
     * Remove duplicates
     * @param bool $applyToThis
     * @return Collection|$this
     */
    public function distinct(bool $applyToThis = false): Collection
    {

        /** @phpstan-ignore-next-line  */
        $collection = new static([]);

        foreach ($this as $key => $value) {
            if (!$collection->valueExists($value)) {
                $collection[$key] = $value;
            }
        }

        if ($applyToThis) {
            $this->array = $collection->array;
            return $this;
        }

        return $collection;

    }

    /**
     * Intersect this collection with array or collection by values
     * @param Collection|array $collection
     * @param bool $applyToThis
     * @return $this
     */
    public function intersect(Collection|array $collection, bool $applyToThis = false): Collection
    {

        if ($collection instanceof Collection) {
            $collection = $collection->toArray();
        }

        $resultArray = array_intersect($this->array, $collection);

        if ($applyToThis) {

            $this->array = $resultArray;
            return $this;

        }
        /** @phpstan-ignore-next-line */
        return new static($resultArray);

    }

    /**
     * Intersect this collection with array or collection by key
     * @param Collection|array $collection
     * @return self
     */
    public function intersectByKey(Collection|array $collection, bool $applyToThis = false): Collection
    {

        if ($collection instanceof Collection) {
            $collection = $collection->toArray();
        }

        $resultArray = array_intersect_key($this->array, $collection);

        if ($applyToThis) {

            $this->array = $resultArray;
            return $this;

        }
        /** @phpstan-ignore-next-line */
        return new static($resultArray);

    }

    /**
     * Get values as array with numeric keys
     */
    public function values(): Collection
    {

        /** @phpstan-ignore-next-line */
        return new static(array_values($this->array));

    }

    public function resetKeys(): self
    {

        $this->array = array_values($this->array);

        return $this;

    }

    /**
     * Transform collection to array
     */
    public function toArray(bool $keepKey = true, bool $recursive = true): array
    {

        $result = [];
        foreach ($this->array as $key => $value) {
            if ($value instanceof Collection && $recursive) {
                $keepKey ? $result[$key] = $value->toArray($keepKey) : $result[] = $value->toArray($keepKey);
            } else {
                $keepKey ? $result[$key] = $value : $result[] = $value;
            }
        }

        return $result;

    }

    /**
     * Filter collection by array of keys
     * @param Collection|array $filter
     */
    public function filterByKeys(Collection|array $filter): Collection
    {

        if (is_array($filter)) {
            $filter = new Collection($filter);
        }

        $result = new Collection();

        foreach ($this as $key => $value) {
            if ($filter->valueExists($key)) {
                $result[$key] = $value;
            }
        }

        return $result;

    }

    /**
     * Filter collection by array of keys
     * @param Collection|array $filter
     */
    public function filterByValues(Collection|array $filter, bool $recursive = false): Collection
    {

        if (is_array($filter)) {
            $filter = new Collection($filter);
        }

        $result = new Collection();

        foreach ($this->array as $key => $value) {

            if ($recursive && $value instanceof Collection) {
                $subValue = $value->filterByValues($filter, $recursive);
                if ($this->count() > 0) {
                    $result[$key] = $subValue;
                }
            } elseif ($filter->valueExists($value)) {
                $result[$key] = $value;
            }

        }

        return $result;

    }

    /**
     * Filter by closure => closure must return true/false/array with keys to filter
     * @return static
     */
    public function filterByCallback(callable $callable): Collection
    {

        /** @phpstan-ignore-next-line */
        $result = new static();
        foreach ($this->array as $key => $value) {
            $test = $callable($key, $value);
            if ($test) {
                $result[$key] = $this[$key];
            }
        }

        return $result;

    }

    /**
     * Sort by key
     * @return $this
     */
    public function sortByKey(bool $recursive = false): self
    {

        // Sort
        ksort($this->array);

        // If recursive, sort sub collections
        if ($recursive) {
            foreach ($this->array as $value) {
                if ($value instanceof Collection) {
                    $value->sortByKey(true);
                }
            }
        }

        return $this;

    }

    /**
     * Sort by values
     * @return $this
     * @throws ForbiddenUsageException
     */
    public function sortByValues(bool $recursive = false): self
    {

        // Sort
        sort($this->array);

        // If recursive, sort sub collections
        if ($recursive) {
            foreach ($this->array as $value) {
                if ($value instanceof Collection) {
                    $value->sortByValues(true);
                }
            }
        }

        return $this;

    }

    /**
     * Sort by values
     * @return $this
     * @throws ForbiddenUsageException
     */
    public function sortByCallback(callable $callable, bool $recursive = false): self
    {

        // Sort
        usort($this->array, $callable);

        // If recursive, sort sub collections
        if ($recursive) {
            foreach ($this->array as $value) {
                if ($value instanceof Collection) {
                    $value->sortByCallback($callable, true);
                }
            }
        }

        return $this;

    }

    /**
     * Sort by keys
     * @return $this
     * @throws ForbiddenUsageException
     */
    public function sortByKeyCallback(callable $callable, bool $recursive = false): self
    {

        // Sort
        uksort($this->array, $callable);

        // If recursive, sort sub collections
        if ($recursive) {
            foreach ($this->array as $value) {
                if ($value instanceof Collection) {
                    $value->sortByKeyCallback($callable, true);
                }
            }
        }

        return $this;

    }

    /**
     * Fill length item with value
     * @return $this
     */
    public function fill(mixed $value, int $length): self
    {

        foreach (range(1, $length) as $unused) {

            $this[] = $value;

        }

        return $this;

    }

    public function last(): mixed
    {

        end($this->array);
        return $this->current();

    }

    public function firstKey(): string|int|null
    {

        $this->rewind();
        return $this->key();

    }

    public function lastKey(): string|int|null
    {

        end($this->array);
        return $this->key();

    }

    public function push(mixed $value): self
    {

        $this[] = $value;

        return $this;

    }

    public function pop(): mixed
    {

        $key = $this->lastKey();
        $value = $this->last();

        unset($this[$key]);

        return $value;

    }

    /**
     * Remove keys
     */
    public function removeKeys(): self
    {

        $this->array = array_values($this->array);

        return $this;

    }

    /**
     * Remove empty values from collection
     * @return $this
     */
    public function removeEmpty(): self
    {

        foreach ($this as $key => $value) {
            if (
                empty($value) ||
                (
                    $value instanceof Collection &&
                    $value->count() == 0
                )
            ) {
                unset($this[$key]);
            }
        }

        /** @phpstan-ignore-next-line  */
        return $this;

    }

    /**
     * Prepare data for json_encode
     */
    public function jsonSerialize(): array
    {

        return $this->toArray();

    }

    /**
     * Map collection on closure
     */
    public function map(callable $callable): Collection
    {

        $array = [];
        foreach ($this as $key => $value) {
            $array[$key] = $callable($key, $value);
        }

        return new Collection($array);

    }



    /**
     * Flatten collection recursively
     * @return Collection
     */
    public function flatten(): Collection
    {

        $result = new Collection();
        foreach ($this as $key => $value) {

            if ($key === null) {
                throw new \LogicException('null key !');
            }

            if (is_array($value)) {
                $value = new Collection($value);
            }

            if ($value instanceof Collection) {

                $result->merge($value->flatten(), true);

            } else {

                if ($result->exists($key)) {
                    $result[] = $value;
                } else {
                    $result[$key] = $value;
                }

            }

        }

        return $result;

    }

    /**
     * Combine these values as key for values
     * @param Collection|array $values
     * @return Collection
     */
    public function combine(Collection|array $values): Collection
    {

        $result = new Collection();
        foreach ($values as $key => $value) {

            if ($key === null) {
                throw new \LogicException('null key !');
            }

            if ($this->exists($key)) {
                $result[$this[$key]] = $value;
            }

        }

        return $result;

    }

    /**
     * Find first occurrence and return his key
     * @param mixed $toFind
     * @return int|string|null
     */
    public function findFirstOccurrence(mixed $toFind): int|string|null
    {

        foreach ($this as $key => $value) {
            if ($toFind === $value) {
                return $key;
            }
        }

        return null;

    }

    /**
     * Remove all values
     * @param array|Collection|scalar $values
     * @return $this
     */
    public function remove(mixed $values): self
    {

        /** @phpstan-ignore-next-line */
        if (!is_scalar($values) && !is_array($values) && !$values instanceof Collection) {
            throw new ForbiddenUsageException(
                'Remove method accepts only scalar, array or instance of '. Collection::class . ' as parameter'
            );
        }

        if (is_scalar($values)) {
            if ($this->valueExists($values)) {
                $this->offsetUnset($this->findFirstOccurrence($values));
            }

            return $this;
        }

        foreach ($values as $key => $value) {

            if ($this->valueExists($value)) {
                $this->offsetUnset($this->findFirstOccurrence($value));
            }

        }

        return $this;

    }

    /**
     * Return Collection of length occurrences after fromKey
     * @param int|string $fromKey
     * @param int $length
     * @return Collection
     * @throws OutOfBoundsException
     */
    public function subPart(int|string $fromKey, int $length): Collection
    {

        if ($length <= 0) {
            throw new OutOfBoundsException('Length must be greater than 0');
        }

        $result = new Collection();
        $count = null;
        foreach ($this as $key => $value) {

            if ($key == $fromKey) {
                $count = 0;
            }

            if ($count !== null) {

                $count++;
                if ($count > $length) {
                    return $result;
                }

                $result[$key] = $value;

            }

        }

        return $result;

    }

    /**
     * Return count of different values
     * @return int
     */
    public function countDistinct(): int
    {

        $found = new Collection();
        $count = 0;
        foreach ($this as $value) {

            if (!$found->valueExists($value)) {
                $found[] = $value;
                $count++;
            }

        }

        return $count;

    }

    /**
     * Return collection of values flatten and deep represented in key separated by dot
     * @return Collection
     */
    public function dot(string $separator = '.'): Collection
    {

        $result = new Collection();
        foreach ($this as $key => $value) {

            if (is_array($value)) {

                $value = new Collection($value);

            }

            if ($value instanceof Collection) {

                $dotedValue = $value->dot($separator);
                foreach ($dotedValue as $dotedKey => $endValue) {
                    $result[$key . $separator . $dotedKey] = $endValue;
                }

            } else {
                $result[$key] = $value;
            }

        }

        return $result;

    }

    /**
     * Reverse dotted collection
     * @return Collection
     */
    public function undot(string $separator = '.'): Collection
    {

        if ($separator == '') {
            throw new ForbiddenUsageException('Separator cannot be empty');
        }

        $result = new Collection();
        foreach ($this as $key => $value) {

            if ($key === null) {
                throw new \LogicException('null key !');
            }

            $element = $result;
            $exploded = explode($separator, (string)$key);
            foreach ($exploded as $num => $keyElement) {

                if (!$element instanceof Collection) {
                    throw new \LogicException('element should be collection !');
                }

                if ($num < count($exploded) - 1) {
                    if ($element->exists($keyElement)) {
                        $element = $element[$keyElement];
                    } else {
                        $element[$keyElement] = new Collection();
                        $element = $element[$keyElement];
                    }
                } else {
                    $element[$keyElement] = $value;
                }

            }

        }

        return $result;

    }

    /**
     * Return collection of this with only one occurrence of values
     * @param bool $applyToThis
     * @return $this
     */
    public function removeDuplicates(bool $applyToThis = false): static
    {

        /** @phpstan-ignore-next-line */
        $result = $applyToThis ? $this : new static();

        $found = new Collection();
        foreach ($this as $key => $value) {

            if (!$found->valueExists($value)) {
                $result[$key] = $value;
                $found[] = $value;
            } elseif ($key !== null && $result->exists($key)) {
                $result->offsetUnset($key);
            }

        }

        if (!$applyToThis) {
        }

        /** @phpstan-ignore-next-line */
        return $result;

    }

    public function foreach(callable $function): self
    {

        foreach ($this as $key => $value) {
            if ($function($value, $key) === false) {
                return $this;
            }
        }

        return $this;

    }

    public function flip(bool $applyToThis = true): Collection
    {

        if ($applyToThis) {

            $this->array = array_flip($this->array);

            return $this;

        }

        return new Collection(array_flip($this->array));

    }

    /**
     * Return page num of collection
     * @param int $page
     * @param int $pageSize
     * @return Collection|$this
     */
    public function paginate(int $page, int $pageSize): Collection
    {

        /** @phpstan-ignore-next-line */
        $result = new static();
        $i = 1;
        foreach ($this as $key => $value) {

            if ($i > ($page - 1) * $pageSize) {

                $result[$key] = $value;
                if ($result->count() == $pageSize) {
                    return $result;
                }

            }

            $i++;

        }

        return $result;

    }

    /**
     * Return a collection whose values are objects created with value in constructor
     * @param string $class
     * @param string|null $colectionClass
     * @return Collection
     * @throws BadClassException
     */
    public function valuesAsObjects(string $class, ?string $colectionClass = null): Collection
    {

        if (!class_exists($class)) {
            throw new BadClassException($class . ' does not exist');
        }

        if ($colectionClass !== null && !class_exists($colectionClass)) {
            throw new BadClassException($colectionClass . ' does not exist');
        }

        /** @phpstan-ignore-next-line */
        $result = $colectionClass == null ? new static() : new $colectionClass();
        if (!$result instanceof Collection) {
            throw new ForbiddenUsageException('$collectionClass must be instance of ' . Collection::class);
        }

        foreach ($this as $key => $value) {

            $result[$key] = new $class($value);

        }

        return $result;

    }

    public function odd(): Collection
    {

        /** @phpstan-ignore-next-line */
        $result = new static();
        $cpt = 1;
        foreach ($this as $key => $value) {

            if ($cpt % 2 !== 0) {
                $result[$key] = $value;
            }

            $cpt++;

        }

        return $result;

    }

    public function even(): Collection
    {

        /** @phpstan-ignore-next-line */
        $result = new static();
        $cpt = 1;
        foreach ($this as $key => $value) {

            if ($cpt % 2 === 0) {
                $result[$key] = $value;
            }

            $cpt++;

        }

        return $result;

    }

    /**
     * Return every n-th element
     * @param int $every
     * @return Collection
     */
    public function nth(int $every, int $offset = 0): Collection
    {

        /** @phpstan-ignore-next-line */
        $result = new static();
        $cpt = 1;
        foreach ($this as $key => $value) {

            if ($every <= $offset) {
                throw new OutOfBoundsException(
                    'Offset cannot be greater or equal to \'every\''
                );
            }

            if (($cpt % $every) - 1 === $offset) {
                $result[$key] = $value;
            }

            $cpt++;

        }

        return $result;

    }

    /**
     * Returns a clone of this without first $num occurrences
     * @param int $num
     * @return Collection
     */
    public function skip(int $num): Collection
    {

        /** @phpstan-ignore-next-line */
        $result = new static();
        $cpt = 1;
        foreach ($this as $key => $value) {

            if ($cpt > $num) {
                $result[$key] = $value;
            }

            $cpt++;

        }

        return $result;

    }

    /**
     * Return first $num elements
     * @param int $num
     * @return mixed
     */
    public function first(int $num = 1): mixed
    {

        /** @phpstan-ignore-next-line */
        $result = new static();
        $cpt = 1;
        foreach ($this as $key => $value) {

            $result[$key] = $value;

            if ($num == 1) {
                return $value;
            }

            $cpt++;

            if ($cpt > $num) {

                return $result;

            }

        }

        return $result;

    }

    /**
     * Transform collection to another collection type
     * @param string $toCollectionClass
     * @return Collection
     * @throws BadClassException
     * @throws ForbiddenUsageException
     */
    public function transform(string $toCollectionClass): Collection
    {

        if (!class_exists($toCollectionClass)) {
            throw new BadClassException($toCollectionClass . ' does not exist');
        }

        $result = new $toCollectionClass();
        if (!$result instanceof Collection) {
            throw new ForbiddenUsageException('$toCollectionClass must represent a class extends ' . Collection::class);
        }

        $result->array = $this->array;

        return $result;

    }

}