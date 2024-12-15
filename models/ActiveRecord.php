<?php

namespace Model;

abstract class ActiveRecord
{
    // database (mysqli instance)
    protected static $db;
    //name of the table in db
    protected static $tableName = '';
    // each column name of a certain table
    protected static $dbColumns = [];
    // Possible erros when trying to create an instance
    protected static $alerts = ["error" => ["error message", "error message 2"], "success" => ["sucess message"]];

    // set the connection to db
    public static function setDB($database)
    {
        self::$db = $database;
    }
    //set alerts
    public static function setAlert($type, $message)
    {
        static::$alerts[$type][] = $message;
        return static::$alerts;
    }
    //validate
    public static function getAlerts(): array
    {
        return static::$alerts;
    }
    // Search a record by its id
    public static function findById(int $id): object | null
    {
        $query = "SELECT * FROM " . static::$tableName . " WHERE id={$id}";

        $result = self::querySQL($query);

        return array_shift($result);
    }
    // Search a record by a column and value
    public static function where(string $column, string $value): object | null
    {
        $value = trim(self::$db->escape_string($value));
        $query = "SELECT * FROM " . static::$tableName . " WHERE {$column} = '{$value}' LIMIT 1";

        $result = self::querySQL($query);

        return array_shift($result);
    }
    // plain sql query (used for advanced queries, for instance, joining tables)
    public static function SQL(string $query): array | bool
    {
        $query = trim($query);

        return $result = self::querySQL($query);
    }
    //Get a certain number of records
    public static function get(int $maxRecords)
    {
        $query = "SELECT * FROM " . static::$tableName . " limit {$maxRecords}";
        $result = self::querySQL($query);
        return $result;
    }
    //Get all records (as an array of objects)
    public static function all(): array
    {
        $query = "SELECT * FROM " . static::$tableName;
        $result = self::querySQL($query);
        return $result;
    }
    //solve a sql query
    public static function querySQL(string $query): array | bool
    {
        //consult db
        $result = self::$db->query($query);
        //final array that will be returned with the records
        $array = [];
        //get records
        if ($result->num_rows > 0) {
            while ($record = $result->fetch_assoc()) {
                //push each record into the array// each record is an object
                $array[] = static::createObject($record);
            }
        }

        $result->free();

        return $array;
    }
    //create objects taking a record from db (active record)
    protected static function createObject(array $record)
    {
        //create a (empty)  object (static) that represents a certain record from db
        $object = new static;
        //create an object that resembles the record brought fron db
        foreach ($record as $key => $value) {
            //check the keys from the record array with the properties of the object
            if (property_exists($object, $key)) {
                $object->$key = $value;
            }
        }
        return $object;
    }
    //save a record (update or create)
    public function save()
    {
        //an id attribute is null when the object is in memory and doesn't exist in the db
        if (!is_null($this->id)) {
            //update  a record
            return $this->update();
        } else {
            //create a new record
            return $this->create();
        }
    }
    //update a record
    protected function update()
    {
        //Get an array with the sanitized attributes
        $attributes = $this->sanitizeAttributes();
        //array with this shape in each index: "key = 'value'"
        $values = [];
        //create dinamic query
        foreach ($attributes as $key => $value) {
            $values[] = "$key = '{$value}'";
        }
        $query = "UPDATE " . static::$tableName . " SET ";
        $query .= join(", ", $values);
        $query .= " WHERE id ='" . self::$db->escape_string($this->id) . "'";
        $query .= "LIMIT 1";

        $result = self::$db->query($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    // create a new record
    protected function create()
    {
        //Get an array with the sanitized attributes
        $attributes = $this->sanitizeAttributes();
        //separate column name and values
        $columns = join(", ", array_keys($attributes));
        $values = join("','", array_values($attributes));

        // Create sql query
        $query = "INSERT INTO " . static::$tableName . " (";
        $query .= $columns . ")";
        //add a ' to the first and last value
        $query .= " VALUES ('" . $values . "')";

        //do the query to save a new record
        $result = self::$db->query($query);
        //insertion queries return a boolean

        return [
            "result" => $result,
            "information" => [
                "insert_id" => self::$db->insert_id,
                "affected_rows" => self::$db->affected_rows,
                "error" => self::$db->error,
                "info" => self::$db->info
            ]
        ];
    }
    //delete a record by its id
    public function delete(): void
    {
        $query = "DELETE FROM " . static::$tableName . " WHERE id={$this->id} LIMIT 1";

        $result = self::$db->query($query);

        if ($result) {
            $this->deleteImg();
            header("location: /admin?result=3");
        } else {
            header("location: /admin?result=4");
        }
    }
    //form an array resembling the record in db
    public function createAttributes(): array
    {
        //associative array with the same structure as the table (propiedades) in db
        $attributes = [];

        foreach (static::$dbColumns as $columnName) {
            //ignore id column cuz it doesn't exist (yet) when creating a new property
            if ($columnName === 'id') continue;
            //create an index with the name of the column and assign its value
            $attributes[$columnName] = $this->$columnName;
        }
        return $attributes;
    }
    public function sanitizeAttributes(): array
    {
        //get an assc array with the property info
        $attributes = $this->createAttributes();
        //sanitize array before saving it into the db
        $sanitizedArray = [];

        foreach ($attributes as $key => $value) {
            //sanitize each value
            $sanitizedArray[$key] = trim(self::$db->escape_string($value));
        }
        return $sanitizedArray;
    }

    //validate inputs
    public function validateInputs(): array
    {
        static::$errors = [];
        return static::$errors;
    }
    //upload image
    public function setImage(String $image)
    {
        //when updating check if an previous image exists
        //an id attribute is only NULL when creating  a new property
        if (!is_null($this->id)) {
            $this->deleteImg();
        }
        //set new image
        if ($image) {
            $this->imagen = $image;
        }
    }
    //delete image from the server
    protected function deleteImg()
    {
        if (isset($this->imagen)) {
            $image = IMAGES_DIR . $this->imagen;
            if (file_exists($image)) {
                unlink($image);
            }
        }
    }
    //sync the object in memory with the changes made by the user
    public function synchronize(array $args = []): void
    {
        //update the current object (the object calling the method ($this))
        if (!empty($args)) {
            foreach ($args as $key => $value) {
                //check if the key is a property in the current object
                if (property_exists($this, $key) && (!is_null($value) && !empty($value))) {
                    $this->$key = trim($value);
                }
            }
        }
    }
}
