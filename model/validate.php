<?php

/*
 * model/
 * contains validation functions for food app
 */


/** validFood() returns true if food is not empty and contains only letters */
function validFood($food)
{
    //$validFoods = array("tacos", "eggs", "pizza");
    // && in_array(strtolower($food), $validFoods);

    /*
    if (!empty($food) && ctype_alpha($food))
        return true;
    else
        return false;
    */

    return !empty($food) && ctype_alpha($food);
}
/** validMeal() returns true if the selected meal is in the list of valid options */
function validMeal($meal)
{
    $validMeals = getMeals();
    return in_array($meal, $validMeals);
}

function validCondiment($selectedConds) {
    $validConds = getCondiments();
    foreach ($selectedConds as $selected) {
        if (!in_array($selected,$validConds)){
            return false;
        }
    }
    return true;
}

