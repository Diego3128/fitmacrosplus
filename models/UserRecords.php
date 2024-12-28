<?php

namespace Model;

use Model\ActiveRecord;

class UserRecords extends ActiveRecord
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
}
