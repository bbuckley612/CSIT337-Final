<?php

/**
 * SEARCH.PHP
 * This file queries database using search params and returns matching users.
 * - Request data is sanitized and validated
 * - SELECT users table with appropriate WHERE clause
 * - On success, returns matching users in JSON format
 */

$sql = "SELECT `uid`, `first`, `last`, `email` FROM `users` WHERE `enabled` = 1 AND ";

$query = filter_input(INPUT_POST, 'query', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);

if (str_contains($query, "@")) {
	$query = str_replace("@", "%", $query);
	$sql .= "LOWER(`email`) LIKE '$query%'";
} else {
	$sql .= "CONCAT(LOWER(`first`), ' ', LOWER(`last`)) LIKE '%$query%'";
}

$result = $conn->query($sql);
$return = array();

while ($row = $result->fetch_assoc()) {
	$return[] = $row;
}

echo json_encode($return);