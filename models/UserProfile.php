<?php

namespace Model;

use Model\ActiveRecord;

class UserProfile extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_profile';
    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'user_id',
        'age',
        'weight',
        'height',
        'gender_id',
        'activity_level_id',
        'goal_id',
    ];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $user_id;
    public $age;
    public $weight;
    public $height;
    public $gender_id;
    public $activity_level_id;
    public $goal_id;
    //to do: attributes like calories, formula..

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->age = $args['age'] ?? '';
        $this->weight = $args['weight'] ?? '';
        $this->height = $args['height'] ?? '';
        $this->gender_id = $args['gender_id'] ?? '';
        $this->activity_level_id = $args['activity_level_id'] ?? '';
        $this->goal_id = $args['goal_id'] ?? '';
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    //validate inputs from the form
    public function validate()
    {
        //age
        $this->validateAge();
        //weight
        $this->validateWeight();
        //height
        $this->validateHeight();
        //gender
        $this->validateGender();
        //activity level
        $this->validateActivityLevel();
        //goal
        $this->validateGoals();

        return self::$alerts;
    }

    public function validateAge()
    {
        if (
            !is_numeric($this->age) ||
            $this->age < 1 ||
            $this->age > 120
        ) {
            self::$alerts["error"][] = "La edad debe ser valida";
        }
    }
    public function validateWeight()
    {
        //values in kilograms
        if (
            !is_numeric($this->weight) ||
            $this->weight < 30 ||
            $this->weight > 500 ||
            (str_contains($this->height, '.') && strlen($this->height) > 6)
        ) {
            self::$alerts["error"][] = "El peso debe ser valido";
        }
    }
    public function validateHeight()
    {
        //values in centimeters
        if (
            !is_numeric($this->height) ||
            $this->height < 50 ||
            $this->height > 300 ||
            strlen($this->height) > 3
        ) {
            self::$alerts["error"][] = "La altura debe ser valida";
        }
    }
    public function validateGender()
    {
        if (
            !is_numeric($this->gender_id) ||
            $this->gender_id < 1 ||
            !$this->gender_id ||
            $this->gender_id > 2
        ) {
            self::$alerts["error"][] = "Selecciona tu genero";
        }
    }
    public function validateActivityLevel()
    {
        if (
            !is_numeric($this->activity_level_id) ||
            $this->activity_level_id < 1 ||
            !$this->activity_level_id
        ) {
            return self::$alerts["error"][] = "Selecciona tu nivel de actividad fisica";
        }
        if (!ActivityLevel::findById($this->activity_level_id)) {
            return self::$alerts["error"][] = "Selecciona un nivel de actividad fisica valido";
        }
    }
    public function validateGoals()
    {
        if (
            !is_numeric($this->goal_id) ||
            $this->goal_id < 1 ||
            !$this->goal_id
        ) {
            return self::$alerts["error"][] = "Selecciona tu meta";
        }
        if (!Goal::findById($this->goal_id)) {
            return self::$alerts["error"][] = "Selecciona una meta valida";
        }
    }
}
