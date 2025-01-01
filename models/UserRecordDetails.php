<?php

namespace Model;

use Model\ActiveRecord;

class UserRecordDetails extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_record_details';
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
    //set user meal id
    public function setMealId($userMealId)
    {
        $this->user_meal_id = $userMealId;
    }
    //set user food id
    public function setUserFoodId($userFoodId)
    {
        $this->user_food_id = $userFoodId;
    }
    //set quantity
    public function setRecordQuantity($portion)
    {
        $this->quantity = $portion;
    }
    //validate portion
    public function validatePortion()
    {
        if (is_numeric($this->quantity) && $this->quantity > 0)  return;

        self::$alerts['error'][] = 'La porción es invalida';

        return self::$alerts;
    }
    //get userRecordDetail by id
    public static function fetchRecordDetail($recordDetailId, $recordId, $userProfileId)
    {
        $query = "SELECT user_record_details.id, user_record_details.user_record_id, ";
        $query .= "user_record_details.user_meal_id, user_record_details.user_food_id, ";
        $query .= "user_record_details.quantity FROM " . self::$tableName;
        $query .=  " INNER JOIN user_records ON user_record_details.user_record_id=user_records.id";
        $query .=  " WHERE  user_record_details.id='{$recordDetailId}' AND";
        $query .=  " user_record_details.user_record_id='{$recordId}' ";
        $query .=  " AND user_records.user_profile_id='{$userProfileId}' ";
        $result = self::SQL($query);
        return $result[0] ?? null;
    }
}
