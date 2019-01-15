<?php
ini_set('display_errors', '1');

  session_start();
  require_once '/home/dbInterface.php';
  processPageRequest();

  function createMessage($movieId){
    $movie = getMovieData($movieId);
    echo "
      <div class='modal-header'>
      <span class='close'>[Close]</span>
      <h2>" . empty($movie) ? 'Title' : $movie['Title'] . " (" . empty($movie) ? 'Year' : $movie['Year'] . ") Rated " . empty($movie) ? 'Rated' : $movie['Rated'] . " " . empty($movie) ? 'Runtime' : $movie['Runtime'] . "<br />" . empty($movie) ? 'Genre' : $movie['Genre'] . "</h2>
    </div>
    <div class='modal-body'>
      <p>Actors: ". empty($movie) ? 'Actors' : implode(",", $movie['Actors']) . "<br />Directed By: " . empty($movie) ? 'Director' : $movie['Director'] . "<br />Written By: " . empty($movie) ? 'Writer' : $movie['Writer'] . "</p>
    </div>
    <div class='modal-footer'>
      <p>" . empty($movie) ? 'Invalid Movie ID!' : $movie['Plot'] . "</p>
    </div>
    ";
  }

  function processPageRequest(){
    if(!isset($_SESSION['name'])){
      header('logon.php');
      exit();
    }
    if(isset($_GET['movie_id'])){
      createMessage($_GET['movie_id']);
    } else {
      createMessage(0);
    }
  }
?>