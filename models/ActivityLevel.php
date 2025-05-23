<?php

namespace Model;

use Model\ActiveRecord;

class ActivityLevel extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'activity_level';
    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'name',
        'description',
        'activity_factor'
    ];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $name;
    public $description;
    public $activity_factor;

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->activity_factor = $args['activity_factor'] ?? '';
    }
}
