<?php
date_default_timezone_set("Europe/Berlin");

include 'Controller.php';
include 'NoteRepo.php';
include 'Note.php';
include 'NoteView.php';
include 'Pagination.php';
include 'Config.php';
include 'Filters.php';

session_start();

$controller = new Controller();
$controller->main();