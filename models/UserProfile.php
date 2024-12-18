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
        'acitivity_level_id',
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
}
