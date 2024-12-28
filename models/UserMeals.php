<?php

namespace Model;

use Model\ActiveRecord;

class UserMeals extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_meals';
    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'user_profile_id',
        'name'
    ];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    protected static $defaultMeals = ['desayuno', 'almuerzo', 'cena'];

    //attributes (columns)
    public $id;
    public $user_profile_id;
    public $name;

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->user_profile_id = $args['user_profile_id'] ?? '';
        $this->name = $args['name'] ?? '';
    }

    // public static function bringUserMeals(){

    // }

    public function setUserProfileId($userProfileId)
    {
        $this->user_profile_id = $userProfileId;
    }
    public function setMealName($mealName)
    {
        $this->name = $mealName;
    }

    public function saveDefaultMeals()
    {
        foreach (self::$defaultMeals as $mealName) {
            $meal = new UserMeals;
            $meal->setUserProfileId($this->user_profile_id);
            $meal->setMealName($mealName);
            $meal->save();
        }
    }
}
