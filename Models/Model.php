<?php


namespace App\Models;


use ReflectionClass;

use App\Engine\Db;

abstract class Model
{
    protected $_db;

    public $id;

    public function __construct()
    {
        $this->_db = new Db();
    }

    /**
     * Return public properties of class
     * @return array
     */
    protected function attributes()
    {
        $class = new ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

    /**
     * Set properties of object
     * @param $values
     * @return $this
     */
    protected function setAttributes($values)
    {
        if (is_array($values)) {
            $attributes = array_flip($this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Create model or models from sql query result
     * @param object|array $raws
     * @return array
     */
    protected function load($raws)
    {
        if(is_array($raws)){
            $properties = [];
            foreach ($raws as $raw){
                $model = new static();
                $properties[] = $model->setAttributes(get_object_vars($raw)) ;
            }

            return $properties;
        } else {
            $model = new static();
            return $model->setAttributes(get_object_vars($raws));
        }

    }

    /**
     * Find model by id
     * @param $id
     * @return array|null
     */
    public function findById($id)
    {
        $label = $this->label();
        $query = $this->_db->prepare("SELECT * FROM ". $label. " WHERE id = :id LIMIT 1");
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $raw = $query->fetch(\PDO::FETCH_OBJ);
        if(!$raw){
            return null;
        }
        $model = $this->load($raw);

        return $model;

    }

    /**
     * Find model by some condition. Exp: $model->findBy(['comment_id' => 10]).
     * @param array $options
     * @return array|null
     */
    public function findBy($options = [])
    {
        if(!empty($options)){
            $label = $this->label();
            $param = array_keys($options)[0];
            $value = array_shift($options);
            $query = $this->_db->prepare("SELECT * FROM ". $label. " WHERE ".$param." = :".$param);
            $query->bindParam(':'.$param, $value, \PDO::PARAM_INT);
            $query->execute();
            $raw = $query->fetchAll(\PDO::FETCH_OBJ);
            if(!$raw){
                return null;
            }
            $model = $this->load($raw);

            return $model;
        } else{
            return null;
        }


    }

    /**
     * Delete raw from table by id
     * @return bool
     */
    public function delete()
    {
        $label = $this->label();

        $query = $this->_db->prepare("DELETE FROM ".$label." WHERE id= :id");
        $query->bindParam(':id', $this->id, \PDO::PARAM_INT);
        return $query->execute();


    }

    /**
     * Get table's raw count
     * @return int
     */
    public function getRawCount()
    {
        $label = $this->label();
        $query = $this->_db->prepare("SELECT * FROM ". $label);
        $query->execute();
        return $query->rowCount();
    }

    /**
     * Return name of table
     * @return string
     *
     */
    abstract protected  function label();



}