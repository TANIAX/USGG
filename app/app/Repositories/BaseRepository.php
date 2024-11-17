<?php

namespace App\Repositories;

use Exception;
use Config\Database;
use ReflectionObject;
use App\Interfaces\IRepository;
use App\Libraries\CodeIgniterExtension\Database\SQLite3\CustomResult;
/**
 * Base class for all repositories.
 * @author Guillaume Cornez
 */
abstract class BaseRepository implements IRepository
{
    public const RESULT_AS_OBJECT  = 1;
    public const RESULT_AS_ARRAY   = 2;
    public const RESULT_AS_CUSTOM  = 3;

    protected $db;
    protected $builder;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * getAll
     *
     * @param  int $result_type
     * @param  object $result_class
     * @return array of objects
     */
    public function getAll($result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder->where('exists', true)->get();
        return $this->getResultAs($query, $result_type, $result_class);
    }
    
    /**
     * getById
     *
     * @param  int $id
     * @param  int $result_type
     * @param  object $result_class
     * @return object
     */
    public function getById($id, $result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $query = $this->builder
        ->where('id', $id)
        ->where('exists', true)
        ->get();
        $data = $this->getResultAs($query, $result_type, $result_class);

        if ($data)
            return $data[0];
        
        return null;
    }
    
    /**
     * insert
     *
     * @param  object $data
     * @param  int $result_type
     * @param  object $result_class
     * @return bool whether the insert was successful or not
     */
    public function insert($data,$result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        return $this->builder->insert($data);
    }
    
    /**
     * update
     *
     * @param  int $id
     * @param  object $data
     * @param  int $result_type
     * @param  object $result_class
     * @return bool whether the update was successful or not
     */
    public function update($id, $data,$result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        return $this->builder
        ->where('id', $id)
        ->update($data);
    }
    
    /**
     * delete
     * @param  int $id
     * @param  bool $force
     * @param  int $result_type
     * @param  object $result_class
     * @return boolean
     */
    public function delete($id,$force,$result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        if($force)
            return $this->builder->where('id', $id)->delete();
        else
            return $this->builder->where('id', $id)->update(['exists' => false]);
    }

    /**
     * deleteRange
     * @param  array $ids
     * @param  bool $force
     * @param  int $result_type
     * @param  object $result_class
     * @return boolean
     */
    public function deleteRange($ids,$force,$result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        if($force)
            return $this->builder->whereIn('id', $ids)->delete();
        else
            return $this->builder->whereIn('id', $ids)->update(['exists' => false]);
    }

    /**
     * Find a single record by a given key-value pair.
     *
     * @param array $associativeArray The key/value pair to search for.
     * @param int $result_type The type of result to return (object or array).
     * @param string|null $result_class The class to use for the returned object.
     * @return mixed|null The result of the query, or null if no result is found.
     */
    public function findOneBy($associativeArray, $result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $key = array_keys($associativeArray)[0];
        $value = $associativeArray[$key];

        if(!is_string($key) || !is_string($value))
            throw new Exception('The associative array must contain string keys and values.');

        $query = $this->builder
        ->where($key,$value)
        ->where('exists', true)
        ->get();
        $data = $this->getResultAs($query, $result_type, $result_class);

        if ($data)
            return $data[0];
        
        return null;
    }

    public function findBy($associativeArray, $result_type = self::RESULT_AS_OBJECT, $result_class = null)
    {
        $key = array_keys($associativeArray)[0];
        $value = $associativeArray[$key];

        if(!is_string($key) || !is_string($value))
            throw new Exception('The associative array must contain string keys and values.');

        $query = $this->builder
        ->where($key,$value)
        ->where('exists', true)
        ->get();
        $data = $this->getResultAs($query, $result_type, $result_class);

        if ($data)
            return $data;
        
        return null;
    }

    /**
     * getResultAs method
     * 
     * This method returns the result of a query in the specified format.
     * 
     * @param mixed $query The query to execute.
     * @param string $result_type The format of the result. Possible values are: 
     *                           - self::RESULT_AS_ARRAY: returns the result as an array.
     *                           - self::RESULT_AS_OBJECT: returns the result as an object.
     *                           - self::RESULT_AS_CUSTOM: returns the result as a custom object. 
     *                             In this case, the $result_class parameter must be provided.
     * @param string|null $result_class The name of the custom class to use when returning the result as a custom object.
     * 
     * @return mixed The result of the query in the specified format.
     */
    public function getResultAs($query, $result_type, $result_class = null)
    {
        $data = null;
        switch ($result_type) {
            case self::RESULT_AS_ARRAY:
                $data = $query->getResultArray();
                break;
            case self::RESULT_AS_OBJECT:
                $data = $query->getResultObject();
                break;
            case self::RESULT_AS_CUSTOM:
                // We need to cast $query from a result class to a custom result class because we want to set a custom fetchObject method
                //! You need to create a custom result class that extends CodeIgniter\Database\{Your sql driver}\Result
                //For this example we created a child class from CodeIgniter\Database\SQLite3\Result
                $query = $this->cast(CustomResult::class,$query); 
                $data = $query->getCustomResultObject($result_class);
                break;
            default:
                $data = $query->getResultObject();
                break;
        }

        return $data;
    }

    /**
     * Casts the properties of a source object to a destination object.
     *
     * @param string|object $destination The destination object or its class name.
     * @param object $sourceObject The source object.
     * @return object The destination object with the properties of the source object.
     */
    private function cast($destination, $sourceObject)
    {
        if(!is_object($sourceObject))
            return $sourceObject;

        if (is_string($destination)) 
            $destination = new $destination();
        
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }
}
