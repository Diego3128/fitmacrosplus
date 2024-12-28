<?php

namespace Model;

use Model\ActiveRecord;

class Units extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'units';
    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'name',
    ];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $name;

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
    }
}
