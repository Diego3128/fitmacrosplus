<?php

namespace Model;

use Model\ActiveRecord;

class UserRequirement extends ActiveRecord
{
    //name of the table in db
    protected static $tableName = 'user_requirements';
    // each column name of a certain table (same names)
    protected static $dbColumns = [
        'id',
        'user_profile_id',
        'basal_metabolic_rate',
        'caloric_requirement',
        'protein_requirement',
        'carb_requirement',
        'fat_requirement',
        'fiber_requirement',
    ];
    // Possible erros when trying to create an instance
    protected static $alerts = [];

    //attributes (columns)
    public $id;
    public $user_profile_id;
    public $basal_metabolic_rate;
    public $caloric_requirement;
    public $protein_requirement;
    public $carb_requirement;
    public $fat_requirement;
    public $fiber_requirement;

    public function __construct(array $args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->user_profile_id = $args['user_profile_id'] ?? null;
        $this->basal_metabolic_rate = $args['basal_metabolic_rate'] ?? '';
        $this->caloric_requirement = $args['caloric_requirement'] ?? '';
        $this->protein_requirement = $args['protein_requirement'] ?? '';
        $this->carb_requirement = $args['carb_requirement'] ?? '';
        $this->fat_requirement = $args['fat_requirement'] ?? '';
        $this->fiber_requirement = $args['fiber_requirement'] ?? '';
    }

    public function setUserProfileId($user_profile_id)
    {
        $this->user_profile_id = $user_profile_id;
    }
    //calculate requirements
    public function calculateRequirements($userProfile)
    {
        //calculate basal metabolic rate
        $this->calculateBMR($userProfile);
        //calculate daily caloric requirement
        $this->calcCaloricRequirement($userProfile);
        //calculate calories depending on the goal
        $this->calcFinalCalories($userProfile);
        //calculate macros
        $this->calcMacros($userProfile);
        //calculate fiber
        $this->fiber_requirement = intval($this->caloric_requirement / 100);
    }
    //calculate basal metabolic rate
    public function calculateBMR($userProfile)
    {
        if ($userProfile->gender_id == 1) {
            //BMR for men //  = (10 x weight in kg) + (6,25 x height in cm) – (5 x age) + 5.

            return $this->basal_metabolic_rate = intval((10 * $userProfile->weight) + (6.25 * $userProfile->height) - (5 * $userProfile->age) + 5);
        }
        if ($userProfile->gender_id == 2) {
            //BMR for women // = 665.1 + (9.56 * weight in kg) + (1.85 * height in cm) – (4.68 * age)
            return intval($this->basal_metabolic_rate = 665.1 + (9.56 * $userProfile->weight) + (1.85 * $userProfile->height) - (4.68 * $userProfile->age));
        }
    }
    //calculate caloric requirement
    public function calcCaloricRequirement($userProfile)
    {
        if ($userProfile->activity_level_id === '1') {
            // BMR * 1.2
            return $this->caloric_requirement = $this->basal_metabolic_rate * 1.2;
        }
        if ($userProfile->activity_level_id === '2') {
            // BMR * 1.375
            return $this->caloric_requirement = $this->basal_metabolic_rate * 1.375;
        }
        if ($userProfile->activity_level_id === '3') {
            // BMR * 1.55
            return $this->caloric_requirement = $this->basal_metabolic_rate * 1.55;
        }
        if ($userProfile->activity_level_id === '4') {
            // BMR * 1.725
            return $this->caloric_requirement = $this->basal_metabolic_rate * 1.725;
        }
        if ($userProfile->activity_level_id === '5') {
            // BMR * 1.9
            return $this->caloric_requirement = $this->basal_metabolic_rate * 1.9;
        }
    }
    //update the caloric requirement depending on the goal
    public function calcFinalCalories($userProfile)
    {
        //lose weight quickly
        if ($userProfile->goal_id === '1') {
            return $this->caloric_requirement = intval($this->caloric_requirement - ($this->caloric_requirement * 0.20));
        }

        //lose weight slowly
        if ($userProfile->goal_id === '2') {
            return $this->caloric_requirement = intval($this->caloric_requirement - ($this->caloric_requirement * 0.10));
        }

        //keep the same calories
        if ($userProfile->goal_id === '3') {
            return intval($this->caloric_requirement);
        }

        //increase weight slowly
        if ($userProfile->goal_id === '4') {
            $this->caloric_requirement = intval($this->caloric_requirement + ($this->caloric_requirement * 0.10));
        }
        //increase weight quicly
        if ($userProfile->goal_id === '5') {
            $this->caloric_requirement = intval($this->caloric_requirement + ($this->caloric_requirement * 0.20));
        }
    }
    //calc macros
    public function calcMacros($userProfile)
    {
        $this->protein_requirement = intval(2 * $userProfile->weight);
        $proteinKcal = $this->protein_requirement * 4;

        $this->fat_requirement = intval(1 * $userProfile->weight);
        $fatKcal = $this->fat_requirement * 9;

        $this->carb_requirement = intval(($this->caloric_requirement - ($proteinKcal +  $fatKcal)) / 4);
    }
}
