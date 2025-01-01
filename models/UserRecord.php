<?php

namespace Model;

use Model\ActiveRecord;

class UserRecord extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_records';
    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'user_profile_id',
        'date'
    ];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $user_profile_id;
    public $date;

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->user_profile_id = $args['user_profile_id'] ?? '';
        $this->date = $args['date'] ?? '';
    }

    //set user profile id
    public function setUserProfileId($userProfileId)
    {
        $this->user_profile_id = $userProfileId;
    }
    //set record date
    public function setRecordDate($date)
    {
        $this->date = $date;
    }

    //get userRecord by date
    public static function getUserRecord($userProfileId, $date)
    {
        $query = "SELECT * FROM ";
        $query .= self::$tableName . " WHERE user_profile_id='{$userProfileId}'";
        $query .= " AND date='{$date}' LIMIT 1";
        $result = self::SQL($query);
        return $result[0] ?? [];
    }

    //get userRecord by id
    public static function fetchUserRecord($recordId, $userProfileId)
    {
        $query = "SELECT * FROM ";
        $query .= self::$tableName . " WHERE user_profile_id='{$userProfileId}' AND id='{$recordId}' LIMIT 1";
        $result = self::SQL($query);
        return $result[0] ?? null;
    }
}
