<?php

namespace Model;

use Model\ActiveRecord;

class UserFoods extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_foods';

    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'user_profile_id',
        'name',
        'brand',
        'serving_size',
        'serving_unit_id',
        'calories',
        'fat',
        'saturated',
        'polyunsaturated',
        'monounsaturated',
        'trans',
        'carbohydrate',
        'fiber',
        'sugars',
        'sugar_alcohols',
        'protein',
        'cholesterol',
        'salt',
        'potassium',
        'sodium',
        'vitamin_a',
        'vitamin_b1',
        'vitamin_b2',
        'vitamin_b3',
        'vitamin_b5',
        'vitamin_b6',
        'vitamin_b11',
        'vitamin_b12',
        'vitamin_c',
        'vitamin_d',
        'vitamin_e',
        'vitamin_k',
        'calcium',
        'copper',
        'iron',
        'magnesium',
        'manganese',
        'phosphorus',
        'selenium',
        'zinc'
    ];

    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $user_profile_id;
    public $name;
    public $brand;
    public $serving_size;
    public $serving_unit_id;
    public $calories;
    public $fat;
    public $saturated;
    public $polyunsaturated;
    public $monounsaturated;
    public $trans;
    public $carbohydrate;
    public $fiber;
    public $sugars;
    public $sugar_alcohols;
    public $protein;
    public $cholesterol;
    public $salt;
    public $potassium;
    public $sodium;
    public $vitamin_a;
    public $vitamin_b1;
    public $vitamin_b2;
    public $vitamin_b3;
    public $vitamin_b5;
    public $vitamin_b6;
    public $vitamin_b11;
    public $vitamin_b12;
    public $vitamin_c;
    public $vitamin_d;
    public $vitamin_e;
    public $vitamin_k;
    public $calcium;
    public $copper;
    public $iron;
    public $magnesium;
    public $manganese;
    public $phosphorus;
    public $selenium;
    public $zinc;

    // Constructor
    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->user_profile_id = $args['user_profile_id'] ?? null;
        $this->name = $args['name'] ?? ''; //
        $this->brand = $args['brand'] ?? ''; //
        $this->serving_size = $args['serving_size'] ?? 0.0; //
        $this->serving_unit_id = $args['serving_unit_id'] ?? ''; //
        $this->calories = $args['calories'] ?? ''; //
        $this->fat = $args['fat'] ?? ''; //
        $this->saturated = $args['saturated'] ?? ''; //
        $this->polyunsaturated = $args['polyunsaturated'] ?? ''; //
        $this->monounsaturated = $args['monounsaturated'] ?? ''; //
        $this->trans = $args['trans'] ?? ''; //
        $this->carbohydrate = $args['carbohydrate'] ?? ''; //
        $this->fiber = $args['fiber'] ?? ''; //
        $this->sugars = $args['sugars'] ?? ''; //
        $this->sugar_alcohols = $args['sugar_alcohols'] ?? ''; //
        $this->protein = $args['protein'] ?? ''; //
        $this->cholesterol = $args['cholesterol'] ?? ''; //
        $this->salt = $args['salt'] ?? ''; //
        $this->potassium = $args['potassium'] ?? ''; //
        $this->sodium = $args['sodium'] ?? ''; //
        $this->vitamin_a = $args['vitamin_a'] ?? ''; //
        $this->vitamin_b1 = $args['vitamin_b1'] ?? ''; //
        $this->vitamin_b2 = $args['vitamin_b2'] ?? ''; //
        $this->vitamin_b3 = $args['vitamin_b3'] ?? ''; //
        $this->vitamin_b5 = $args['vitamin_b5'] ?? ''; //
        $this->vitamin_b6 = $args['vitamin_b6'] ?? ''; //
        $this->vitamin_b11 = $args['vitamin_b11'] ?? ''; //
        $this->vitamin_b12 = $args['vitamin_b12'] ?? ''; //
        $this->vitamin_c = $args['vitamin_c'] ?? ''; //
        $this->vitamin_d = $args['vitamin_d'] ?? ''; //
        $this->vitamin_e = $args['vitamin_e'] ?? ''; //
        $this->vitamin_k = $args['vitamin_k'] ?? ''; //
        $this->calcium = $args['calcium'] ?? ''; //
        $this->copper = $args['copper'] ?? ''; //
        $this->iron = $args['iron'] ?? ''; //
        $this->magnesium = $args['magnesium'] ?? ''; //
        $this->manganese = $args['manganese'] ?? ''; //
        $this->phosphorus = $args['phosphorus'] ?? ''; //
        $this->selenium = $args['selenium'] ?? ''; //
        $this->zinc = $args['zinc'] ?? ''; //
    }

    //set user profile id
    public function setUserProfileId($userProfileId)
    {
        $this->user_profile_id = $userProfileId;
    }

    //bring a single food
    public static function fetchUserFood($userProfileId, $foodId)
    {
        $query = "SELECT * FROM " . static::$tableName;
        $query .= " WHERE id='{$foodId}' AND user_profile_id='{$userProfileId}'";

        $result = self::querySQL($query);
        return array_shift($result) ?? null;
    }

    // Validation
    public function validate()
    {
        $this->validateRequiredFields();
        $this->validateMacros();
        $this->validateAttributes();
        $this->calculateCalories();
        return self::$alerts;
    }

    // Validar campos obligatorios
    private function validateRequiredFields()
    {
        if (!$this->name || strlen(!$this->name) > 50) {
            self::$alerts['error'][] = 'El nombre no existe o no es válido.';
        }

        if (!$this->serving_size || !is_numeric($this->serving_size) || $this->serving_size <= 0) {
            self::$alerts['error'][] = 'El tamaño de la porción debe ser un número positivo.';
        }

        if (!$this->serving_unit_id || !$this->serving_unit_id < 0) {
            self::$alerts['error'][] = 'La unidad de porción no es valida.';
        } elseif (!Units::findById($this->serving_unit_id)) {
            self::$alerts['error'][] = 'La unidad de porción no es valida.';
        }
    }

    // Validate macros
    private function validateMacros()
    {
        if (!$this->fat) $this->fat = 0;

        if ($this->fat < 0 || $this->fat > 100000) {
            self::$alerts['error'][] = 'El valor de grasas es inválido o fuera de rango.';
            $this->fat = 0;
        }

        if (!$this->carbohydrate) $this->carbohydrate = 0;

        if ($this->carbohydrate < 0 || $this->carbohydrate > 100000) {
            self::$alerts['error'][] = 'El valor de carbohidratos es inválido o fuera de rango.';
            $this->carbohydrate = 0;
        }

        if (!$this->protein) $this->protein = 0;

        if ($this->protein < 0 || $this->protein > 100000) {
            self::$alerts['error'][] = 'El valor de proteínas es inválido o fuera de rango.';
            $this->protein = 0;
        }
    }

    //calc food total calories
    public function calculateCalories()
    {
        $this->calories = intval(($this->fat * 9) + ($this->carbohydrate * 4) + ($this->protein * 4), 10);
    }

    // Validate optional attributes and set default values
    private function validateAttributes()
    {
        $attributes = [
            'saturated',
            'polyunsaturated',
            'monounsaturated',
            'trans',
            'fiber',
            'sugars',
            'sugar_alcohols',
            'cholesterol',
            'salt',
            'potassium',
            'sodium',
            'vitamin_a',
            'vitamin_b1',
            'vitamin_b2',
            'vitamin_b3',
            'vitamin_b5',
            'vitamin_b6',
            'vitamin_b11',
            'vitamin_b12',
            'vitamin_c',
            'vitamin_d',
            'vitamin_e',
            'vitamin_k',
            'calcium',
            'copper',
            'iron',
            'magnesium',
            'manganese',
            'phosphorus',
            'selenium',
            'zinc'
        ];

        foreach ($attributes as $attribute) {

            if (!$this->$attribute) {
                $this->$attribute = 0;
                continue;
            }
            //set default value if its not correct
            if (!is_numeric($this->$attribute) || $this->$attribute < 0 || $this->$attribute > 10000) {
                $this->$attribute = 0;
            }
        }
    }
    //
    public function calculateCaloriesByQuantity($quantity)
    {
        if (!is_numeric($quantity) || $quantity <= 0) {
            return 0; // No valid cases
        }

        return ($this->calories / $this->serving_size) * $quantity;
    }
    //
    public function calculateMacrosByQuantity($quantity)
    {
        if (!is_numeric($quantity) || $quantity <= 0) {
            return [
                'protein' => 0,
                'carbohydrate' => 0,
                'fat' => 0,
            ];
        }

        $factor = $quantity / $this->serving_size;

        return [
            'protein' => $this->protein * $factor,
            'carbohydrate' => $this->carbohydrate * $factor,
            'fat' => $this->fat * $factor,
        ];
    }

    //
    public function getMacroDistribution()
    {
        $totalCalories = $this->calories;
        if ($totalCalories <= 0) {
            return [
                'protein' => 0,
                'carbohydrate' => 0,
                'fat' => 0,
            ];
        }

        $proteinCalories = $this->protein * 4; // 1g  protein = 4 kcal
        $carbCalories = $this->carbohydrate * 4; // 1g  carbs = 4 kcal
        $fatCalories = $this->fat * 9; // 1g  fat = 9 kcal

        return [
            'protein' => ($proteinCalories / $totalCalories) * 100,
            'carbohydrate' => ($carbCalories / $totalCalories) * 100,
            'fat' => ($fatCalories / $totalCalories) * 100,
        ];
    }

    public function calculateMicronutrientsByQuantity($quantity)
    {
        if (!is_numeric($quantity) || $quantity <= 0) {
            return [];
        }

        $micronutrients = [
            'cholesterol',
            'sodium',
            'potassium',
            'calcium',
            'iron',
            'vitamin_a',
            'vitamin_b1',
            'vitamin_b2',
            'vitamin_b3',
            'vitamin_b5',
            'vitamin_b6',
            'vitamin_b11',
            'vitamin_b12',
            'vitamin_c',
            'vitamin_d',
            'vitamin_e',
            'vitamin_k',
            'copper',
            'magnesium',
            'manganese',
            'phosphorus',
            'selenium',
            'zinc',
        ];

        $results = [];
        $factor = $quantity / $this->serving_size;

        foreach ($micronutrients as $nutrient) {
            if (isset($this->$nutrient)) {
                $results[$nutrient] = $this->$nutrient * $factor;
            }
        }

        return $results;
    }


    public static function calculateMealCalories(array $foods)
    {
        $totalCalories = 0;

        foreach ($foods as $food) {
            if ($food instanceof UserFoods) {
                $totalCalories += $food->calculateCaloriesByQuantity($food->quantity ?? 1);
            }
        }

        return $totalCalories;
    }
}
