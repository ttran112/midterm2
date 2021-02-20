<?php
/** Create a food order form */

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

//Require files
require_once('vendor/autoload.php');
require_once('model/data-layer.php');
require_once('model/validate.php');

//Instantiate Fat-Free
$f3 = Base::instance();

//Turn on Fat-Free error reporting
$f3->set('DEBUG', 3);

//Define a default route
$f3->route('GET /', function() {

    //Display a view
    $view = new Template();
    echo $view->render('views/home.html');
});

//Define an order route
$f3->route('GET|POST /survey', function($f3) {

    //var_dump($_POST);

    //If the form has been submitted
    if ($_SERVER['REQUEST_METHOD']=='POST') {

        //Get the data from the POST array
        $userFood = $_POST['food'];
        //If the data is valid --> Store in session
        if(validFood($userFood)) {
            $_SESSION['food'] = $userFood;
        }
        //Data is not valid -> Set an error in F3 hive
        else {
            $f3->set('errors["food"]', "Food cannot be blank");
        }


        if(isset($_POST['conds'])) {
            $userCondiments = $_POST['conds'];

            if(validCondiment($userCondiments)) {
                $_SESSION['conds'] = implode(", ", $userCondiments);
            }
            else {
                $f3->set('errors["conds"]', "Go away, evildoer!");
            }

        }
        else {
            $f3->set('errors["conds"]', "Please choose 1");

        }

        //If there are no errors, redirect to /order2
        if(empty($f3->get('errors'))) {
            $f3->reroute('/summary');  //GET
        }
    }

    //var_dump($_POST);
    //$f3->set('meals', getMeals());
    $f3->set('condiments', getCondiments());
    $f3->set('userFood', isset($userFood) ? $userFood : "");

    //Display a view
    $view = new Template();
    echo $view->render('views/survey.html');
});


//Define a summary route
$f3->route('GET|POST /summary', function() {

    //Display a view
    $view = new Template();
    echo $view->render('views/summary.html');

    session_destroy();
});

//Run Fat-Free
$f3->run();