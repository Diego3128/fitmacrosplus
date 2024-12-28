<?php

namespace Model;

use Model\ActiveRecord;

class UserFoodBasic extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_foods';

    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'name',
        'serving_size',
        'serving_unit_id',
        'calories',
        'fat',
        'carbohydrate',
        'protein'
    ];

    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $name;
    public $serving_size;
    public $serving_unit_id;
    public $calories;
    public $fat;
    public $carbohydrate;
    public $protein;

    //bring food basic data
    public static function fetchBasicFoodData($userProfileId, $foodId)
    {
        $query = "SELECT * FROM " . static::$tableName;
        $query .= " WHERE id='{$foodId}' AND user_profile_id='{$userProfileId}'";

        $result = self::querySQL($query);
        return array_shift($result) ?? null;
    }
}
