<?php

namespace Vich\UploaderBundle\Handler;

use Vich\UploaderBundle\Exception\MappingNotFoundException;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Mapping\PropertyMappingFactory;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
abstract class AbstractHandler
{
    /**
     * @var PropertyMappingFactory
     */
    protected $factory;

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @param PropertyMappingFactory $factory The mapping factory
     * @param StorageInterface       $storage The storage
     */
    public function __construct(PropertyMappingFactory $factory, StorageInterface $storage)
    {
        $this->factory = $factory;
        $this->storage = $storage;
    }

    /**
     * @param object|array $obj
     * @param string       $fieldName
     * @param string|null  $className
     *
     * @return PropertyMapping|null
     */
    protected function getMapping($obj, $fieldName, $className = null)
    {
        $mapping = $this->factory->fromField($obj, $fieldName, $className);

        if (null === $mapping) {
            throw new MappingNotFoundException(sprintf('Mapping not found for field "%s"', $fieldName));
        }

        return $mapping;
    }
}
