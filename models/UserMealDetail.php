<?php

namespace Model;

use Model\ActiveRecord;

class UserMealDetail extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_meal_detail';
    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'meal_id',
        'meal_name',
        'date',
        'record_id',
        'record_detail_id',
        'food',
        'protein',
        'carbs',
        'fat',
        'calories',
        'original_serving_size',
        'unit',
        'consumed_quantity'
    ];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //stats of each meal
    protected static $mealStats = [];
    // general stats adding all meals
    protected static $generalStats = [];
    //percentages
    protected static $statPercentages = [];

    //attributes (columns)
    public $meal_id;
    public $meal_name;
    public $date;
    public $record_id;
    public $record_detail_id;
    public $food;
    public $protein;
    public $carbs;
    public $fat;
    public $calories;
    public $original_serving_size;
    public $unit;
    public $consumed_quantity;

    public function __construct(array $args = [])
    {
        $this->meal_id = $args['meal_id'] ?? '';
        $this->meal_name = $args['meal_name'] ?? '';
        $this->date = $args['date'] ?? '';
        $this->record_id = $args['record_id'] ?? '';
        $this->record__detail_id = $args['record__detail_id'] ?? '';
        $this->food = $args['food'] ?? '';
        $this->protein = $args['protein'] ?? '';
        $this->carbs = $args['carbs'] ?? '';
        $this->fat = $args['fat'] ?? '';
        $this->calories = $args['calories'] ?? '';
        $this->original_serving_size = $args['original_serving_size'] ?? '';
        $this->unit = $args['unit'] ?? '';
        $this->consumed_quantity = $args['consumed_quantity'] ?? '';
    }

    //
    public static function getMealDetails(int $userProfileId, string $date, string $userRecordId): array
    {
        $query = "SELECT 
                    user_meals.id AS meal_id,
                    user_meals.name AS meal_name,
                    user_records.date AS date,
                    user_records.id AS 'record_id',
                    user_record_details.id AS 'record_detail_id',
                    user_foods.name AS food,
                    user_foods.protein AS protein,
                    user_foods.carbohydrate AS carbs,
                    user_foods.fat AS fat,
                    user_foods.calories AS calories,
                    user_foods.serving_size AS original_serving_size,
                    units.name AS unit,
                    user_record_details.quantity AS consumed_quantity
                  FROM user_meals
                  LEFT JOIN user_records ON user_meals.user_profile_id = user_records.user_profile_id
                  LEFT JOIN user_record_details ON user_meals.id = user_record_details.user_meal_id
                  LEFT JOIN user_foods ON user_record_details.user_food_id = user_foods.id
                  LEFT JOIN units ON user_foods.serving_unit_id = units.id
                  WHERE user_meals.user_profile_id = '{$userProfileId}' AND user_records.date = '{$date}'
                   AND user_record_details.user_record_id='{$userRecordId}'";

        $result = static::SQL($query);
        return $result;
    }

    /**
     * Calculate proportions using the rule of three.
     * @param float|null $value The original value to adjust
     * @param float|int $consumedQuantity The quantity consumed by the user.
     * @param float|int $originalServingSize The original serving size of the food.
     * @return float|null The proportion value or null if inputs are invalid.
     */
    public static function calculateProportion(?float $value, float | int $consumedQuantity, float | int $originalServingSize): ?float
    {
        if ($value === null || $originalServingSize === 0) {
            return null; // Prevent division by zero or calculate for invalid values.
        }
        return ($value * $consumedQuantity) / $originalServingSize;
    }

    /**
     * Process meal details and calculate nutrient proportions.
     * @param array $mealDetails Array of UserMealDetail objects.
     * @return array Processed mealDetails array with calculated proportions.
     */
    public static function processMealDetails(array  $mealDetails): array
    {
        foreach ($mealDetails as $mealDetail) {
            // Check if the meal has food data before calculating proportions
            if ($mealDetail->food) {
                $mealDetail->protein = self::calculateProportion(
                    (float) $mealDetail->protein,
                    (float) $mealDetail->consumed_quantity,
                    (float) $mealDetail->original_serving_size
                );

                $mealDetail->carbs = self::calculateProportion(
                    (float) $mealDetail->carbs,
                    (float) $mealDetail->consumed_quantity,
                    (float) $mealDetail->original_serving_size
                );

                $mealDetail->fat = self::calculateProportion(
                    (float) $mealDetail->fat,
                    (float) $mealDetail->consumed_quantity,
                    (float) $mealDetail->original_serving_size
                );

                $mealDetail->calories = self::calculateProportion(
                    (float) $mealDetail->calories,
                    (float) $mealDetail->consumed_quantity,
                    (float) $mealDetail->original_serving_size
                );
            }
        }
        return $mealDetails;
    }
    //calc meal stats and general stats
    public static function calcMealStats($userMeals, $mealDetails)
    {
        // initial structure for stadistics of each meal
        $initStats = [
            "calories" => 0,
            "protein" => 0,
            "carbs" => 0,
            "fat" => 0
        ];

        // start stadistics
        self::$mealStats = [];
        foreach ($userMeals as $userMeal) {
            self::$mealStats[$userMeal->name] = $initStats;
        }

        // Calc calories and macros for each meal
        foreach ($mealDetails as $mealDetail) {
            $mealName = $mealDetail->meal_name;
            if (isset(self::$mealStats[$mealName])) {
                self::$mealStats[$mealName]["calories"] += $mealDetail->calories;
                self::$mealStats[$mealName]["protein"] += $mealDetail->protein;
                self::$mealStats[$mealName]["carbs"] += $mealDetail->carbs;
                self::$mealStats[$mealName]["fat"] += $mealDetail->fat;
            }
        }

        //restart calories and macros
        self::$generalStats = [
            "totalCalories" => 0,
            "totalProtein" => 0,
            "totalCarbs" => 0,
            "totalFat" => 0,
        ];

        foreach (self::$mealStats as $stats) {
            self::$generalStats["totalCalories"] += round($stats["calories"], 1);
            self::$generalStats["totalProtein"] += round($stats["protein"], 1);
            self::$generalStats["totalCarbs"] += round($stats["carbs"], 1);
            self::$generalStats["totalFat"] += round($stats["fat"], 1);
        }

        return [self::$mealStats, self::$generalStats];
    }

    public static function calcStatPercentage($userRequirements, $generalStats)
    {
        //calc percentages
        self::$statPercentages = [
            "calorieProgress" => 0,
            "proteinProgress" => 0,
            "carbsProgress" => 0,
            "fatProgress" => 0,
        ];
        $userCalories = $userRequirements->caloric_requirement;
        $userProtein = $userRequirements->protein_requirement;
        $userCarbs = $userRequirements->carb_requirement;
        $userFat = $userRequirements->fat_requirement;

        self::$statPercentages["calorieProgress"] = self::calculatePercentage($generalStats["totalCalories"], $userCalories);
        self::$statPercentages["proteinProgress"] = self::calculatePercentage($generalStats["totalProtein"], $userProtein);
        self::$statPercentages["carbsProgress"] = self::calculatePercentage($generalStats["totalCarbs"], $userCarbs);
        self::$statPercentages["fatProgress"] = self::calculatePercentage($generalStats["totalFat"], $userFat);

        return self::$statPercentages;
    }

    //  Calculate the percentage of a value relative to a maximum value.
    public static function calculatePercentage(float|int $currentValue, float|int $maxValue, int $decimals = 1): float
    {
        if ($maxValue <= 0) return 0.0;

        $percentage = ($currentValue / $maxValue) * 100;

        return round($percentage, $decimals);
    }
}
