<?php

namespace Model;

use Model\ActiveRecord;

class UserRecordDetails extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_records_details';
    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'user_record_id',
        'user_meal_id',
        'user_food_id',
        'quantity'
    ];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $user_record_id;
    public $user_meal_id;
    public $user_food_id;
    public $quantity;

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->user_record_id = $args['user_record_id'] ?? '';
        $this->user_meal_id = $args['user_meal_id'] ?? '';
        $this->user_food_id = $args['user_food_id'] ?? '';
        $this->quantity = $args['quantity'] ?? '';
    }

    //set user record id
    public function setUserRecordId($userRecordId)
    {
        $this->user_record_id = $userRecordId;
    }
}
