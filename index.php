<?php
/*
 * Author Thanh Tran
 * description: main page for the viewing dating webpage
 * link: index.php
 */
//Turn on error reporting
ini_set('display_errors',1);
error_reporting(E_ALL);

//start session
session_start();

//require the autoload file
require_once ('vendor/autoload.php');
require_once ('model/data-layer.php');
require_once ('model/validate.php');


//create an instance of the Base class
$f3 = Base::instance();
//set a debug
$f3 -> set('DEBUG',3);


// define a default route
$f3 -> route('GET|POST /', function ()
{
    $view = new Template();
    echo $view -> render('views/home.html');
}
);

//Define an order route
$f3->route('GET|POST /survey', function($f3) {

    if ($_SERVER['REQUEST_METHOD']=='POST') {

        //Get the data from the POST array
        $userFood = trim($_POST['food']);

        //If the data is valid --> Store in session
        if(validFood($userFood)) {
            $_SESSION['food'] = $userFood;
        }
        //Data is not valid -> Set an error in F3 hive
        else {
            $f3->set('errors["food"]', "Food cannot be blank and must contain only characters");
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
        //If there are no errors, redirect user to summary page
        if (empty($f3->get('errors'))) {
            $f3->reroute('/summary');
        }


    }

    //var_dump($_POST);
    $f3->set('meals', getMeals());
    $f3->set('userFood', isset($userFood) ? $userFood : "");

    $f3->set('condiments', getCondiments());




    $view = new Template();
    echo $view->render('views/survey.html');
});

$f3->route('GET|POST /summary', function() {

    //echo "<p>POST:</p>";
    //var_dump($_POST);

    //echo "<p>SESSION:</p>";
    //var_dump($_SESSION);

    //Add data from form2 to Session array
//    if(isset($_POST['conds'])) {
//        $_SESSION['conds'] = implode(", ", $_POST['conds']);
//    }

    //Display a view
    $view = new Template();
    echo $view->render('views/summary.html');

    session_destroy();
});

//Define an order route


//run fat free
$f3 -> run();
