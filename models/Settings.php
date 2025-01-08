<?php

namespace Model;

use Model\ActiveRecord;

class Settings extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_settings';

    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'user_email',
        'user_name',
        'user_lastname',
        'formula_name',
        'formula_expression'
    ];

    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $user_email;
    public $user_name;
    public $user_lastname;
    public $formula_name;
    public $formula_expression;

    //bring food basic data
    public static function fetchBasicSettings($userProfileId)
    {
        $query = "SELECT user.email AS 'user_email', user.name AS 'user_name', ";
        $query .= "user.lastname AS 'user_lastname', ";
        $query .= "formula.name AS 'formula_name', ";
        $query .= "formula.formula_expression AS 'formula_expression' ";
        $query .= "FROM user_profile  ";
        $query .= "INNER JOIN user ON user_profile.user_id=user.id ";
        $query .= "INNER JOIN formula ON user_profile.formula_id = formula.id ";
        $query .= "WHERE user_profile.id='{$userProfileId}' ";

        $result = self::querySQL($query);
        return array_shift($result) ?? null;
    }
}
