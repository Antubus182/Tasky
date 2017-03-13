<?php
//this file creates the actual html file
function test($a){
	$a=$a*2;
	return $a;
}

function generatePage($projectData,$usr){
	$config=json_decode(file_get_contents("../config.json"));
  $startingHtml='<!doctype html>
                    <html class="no-js" lang="en">
                    <head>
                      <meta charset="utf-8" />
                      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                      <title>Tasky by Niels</title>
                      <link rel="stylesheet" href="../css/foundation-icons.css">
                      <link rel="stylesheet" href="../css/foundation.css">
                      <link rel="stylesheet" href="../css/tasky.css">

                      <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon">
                    </head>
                    <body>
                      <div id="TaskyWrapper" class="off-canvas-wrapper"></div>
                        <div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>';
	$menuHtml='
		<div class="off-canvas position-left reveal-for-large" id="my-info" data-off-canvas data-position="left">
          <div class="row column">
            <br>
            <img class="thumbnail" id="gebr" src="http://loremflickr.com/225/175">
            <h5>Welcome '.$usr->name.'</h5>
            <p>'.$usr->about.'</p>
            <a type="button" href="logout.php" id="logout" class="alert button">Logout</a>
          </div>
          <div class="row column">
            <br>
            <button type="button" id="addProject'.$usr->id.'" class="success button">+ Add Project</button>
          </div>
        </div>

        <div class="off-canvas-content" data-off-canvas-content>
          <div class="title-bar hide-for-large">
            <div class="title-bar-left">
              <button class="menu-icon" type="button" data-open="my-info"></button>
              <span class="title-bar-title">Menu</span>
            </div>
          </div>
		';
	$introHtml='
		  <div class="callout primary">
            <div class="row column">
              <h1>Welkom to Tasky Projectmanager</h1>
              <p class="lead">'.$config->introtext.'</p>
            </div>
          </div>
	';

	$projectlisting='<hr><div id="projectContainers">';
  foreach($projectData as $project){
    $building='<div id="project'.$project["id"].'" class="callout alert"><div class="row column clearfix"><h1 id="p'.$project["id"].'" class="float-left">'.$project['name'].'</h1>';
    $building.='<button type="button" id="projectdone'.$project["id"].'" class="pfin info button float-right">Project Finished</button>';
    $building.='<button type="button" id="taskadd'.$project["id"].'" class="success button float-right">+ Add Task</button></div><hr>';
    $building.='<div class="row small-up-1 medium-up-3 large-up-4" data-equalizer="section'.$project["id"].'" data-equalize-by-row="true">';
    foreach($project["tasks"] as $task){
      //if(!$task["done"]){
        if($task["done"]){
          $building.='<div class="column hidden"><div class="taskslip '.$task["color"].'">';
        }
        else{
          $building.='<div class="column"><div class="taskslip '.$task["color"].'" data-equalizer-watch="section'.$project["id"].'">';
        }
        //$building.='<div class="taskslip '.$task["color"].'" data-equalizer-watch="section'.$project["id"].'">';
        $building.='<h5>'.$task["tname"].'</h5><ul>';
        foreach($task["subtasks"] as $subtask){
          if($subtask["done"]){
            $building.='<li id=subtask'.$subtask["id"].' class="subdone"><input type="checkbox" checked>'.$subtask["subname"].'</li>';
          }
          else{
            $building.='<li id=subtask'.$subtask["id"].'><input type="checkbox">'.$subtask["subname"].'</li>';
          }
        }
        $building.='</ul><button type="button" id="sub'.$project["id"].'-'.$task["id"].'" class="success button">+ Add subtask</button><br>';
        $building.='<button type="button" id="donetask'.$project["id"].'-'.$task["id"].'" class="small alert button">Task completed</button>';
        $building.='</div></div>';
    }
    $building.='</div></div>';
    $projectlisting.=$building;
  }

	$closingHtml='</div></div></div>
                  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
                  <script src="../js/foundation.js"></script>
                  <script src="../js/tasks.js"></script>
                  <script>
                  $(document).foundation();
                  </script>
                </body>';

	$completePage=$startingHtml.$menuHtml.$introHtml.$projectlisting.$closingHtml;
	return $completePage;
}

/*
$projectData is an array with all projects that need to be renderd
each project is an assocarray with the following structure:
"name"=projecttitle
"owner"=integer with project owner
"description"=a project description
"color"=a hexcolor code for the background (no #)
"tasks"= an array with the tasks
	"tname"=the task name
	"color"= an integer representing the class determining the postit color
	"done"=an integer 1 or 0 to check if the task is done
	"subtasks"= an array with subtasks
		in this array the key is the title of the subtask and the value is an integer stating wheter or not it is completed

using an assocarray instead of an object is to provide the possiblitity to in a future release directly return a json object to the javascript file
*/
?>
