<?php
defined('LOCALHOST') ? null : define('LOCALHOST', 'localhost');
defined('USERNAME') ? null : define('USERNAME', 'root');
defined('PASSWORD') ? null : define('PASSWORD', '');
defined('DBNAME') ? null : define('DBNAME', 'cleopatra_database');


$conn;
function connect() {
    global $conn;
    $conn = mysqli_connect(LOCALHOST, USERNAME, PASSWORD, DBNAME) or die("Connection to DB failed");
}


function query($q) {
    global $conn;
    $results = mysqli_query($conn, $q);
    check($results);
    return $results;
}

function check($results) {
    if (!$results) {
        echo mysqli_error($results);
    }
}



?>