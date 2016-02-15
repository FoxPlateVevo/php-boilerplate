<?php
$this->respond("GET", "/?", function ($request, $response) {
    // Show all users
    echo "
    <h1>User site</h1>
    <a href='/'>Home</a>
    <a href='/users/view/12345'>User 12345</a>
    ";
});

$this->respond("GET", "/view/[:id]", function ($request, $response) {
    // Show a single user
    echo "
    <h1>User {$request->id}</h1>
    <a href='/users'>Users</a>
    ";
});