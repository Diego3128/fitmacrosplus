<?php

namespace Model;

use Model\ActiveRecord;

class Formula extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'formula';
    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'name',
        'description',
        'formula_expression',
        'gender_id',
    ];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $name;
    public $description;
    public $formula_expression;
    public $gender_id;

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->name = $args['name'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->formula_expression = $args['formula_expression'] ?? '';
        $this->gender_id = $args['gender_id'] ?? '';
    }
}
