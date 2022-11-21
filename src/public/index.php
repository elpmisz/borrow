<?php
require_once(__DIR__ . "/vendor/autoload.php");
$Router = new AltoRouter();

$Router->map("GET", "/", function () {
  require(__DIR__ . "/views/home/login.php");
});
$Router->map("GET", "/home", function () {
  require(__DIR__ . "/views/home/index.php");
});
$Router->map("GET", "/error", function () {
  require(__DIR__ . "/views/home/error.php");
});
$Router->map("GET", "/login", function () {
  require(__DIR__ . "/views/home/login.php");
});
$Router->map("GET", "/register", function () {
  require(__DIR__ . "/views/home/register.php");
});
$Router->map("GET", "/forget", function () {
  require(__DIR__ . "/views/home/forget.php");
});
$Router->map("GET", "/logout", function () {
  require(__DIR__ . "/views/home/logout.php");
});
$Router->map("POST", "/auth/[**:params]", function ($params) {
  require(__DIR__ . "/views/home/action.php");
});

$Router->map("GET", "/system", function () {
  require(__DIR__ . "/views/systems/index.php");
});
$Router->map("POST", "/system/[**:params]", function ($params) {
  require(__DIR__ . "/views/systems/action.php");
});

$Router->map("GET", "/users", function () {
  require(__DIR__ . "/views/users/index.php");
});
$Router->map("POST", "/users/data", function () {
  require(__DIR__ . "/views/users/data.php");
});
$Router->map("GET", "/users/profile", function () {
  require(__DIR__ . "/views/users/profile.php");
});
$Router->map("GET", "/users/request", function () {
  require(__DIR__ . "/views/users/request.php");
});
$Router->map("GET", "/users/item", function () {
  require(__DIR__ . "/views/users/item.php");
});
$Router->map("GET", "/users/view/[**:params]", function ($params) {
  require(__DIR__ . "/views/users/view.php");
});
$Router->map("POST", "/users/[**:params]", function ($params) {
  require(__DIR__ . "/views/users/action.php");
});

$Router->map("GET", "/items", function () {
  require(__DIR__ . "/views/items/index.php");
});
$Router->map("POST", "/items/data", function () {
  require(__DIR__ . "/views/items/data.php");
});
$Router->map("GET", "/items/request", function () {
  require(__DIR__ . "/views/items/request.php");
});
$Router->map("GET", "/items/view/[**:params]", function ($params) {
  require(__DIR__ . "/views/items/view.php");
});
$Router->map("POST", "/items/[**:params]", function ($params) {
  require(__DIR__ . "/views/items/action.php");
});

$Router->map("GET", "/borrow", function () {
  require(__DIR__ . "/views/borrow/index.php");
});
$Router->map("GET", "/borrow/manage", function () {
  require(__DIR__ . "/views/borrow/manage.php");
});
$Router->map("GET", "/borrow/item", function () {
  require(__DIR__ . "/views/borrow/item.php");
});
// $Router->map("POST", "/borrow/data", function () {
//   require(__DIR__ . "/views/borrow/data.php");
// });
// $Router->map("GET", "/borrow/request", function () {
//   require(__DIR__ . "/views/borrow/request.php");
// });
// $Router->map("GET", "/borrow/view/[**:params]", function ($params) {
//   require(__DIR__ . "/views/borrow/view.php");
// });
// $Router->map("POST", "/borrow/[**:params]", function ($params) {
//   require(__DIR__ . "/views/borrow/action.php");
// });


$match = $Router->match();

if (is_array($match) && is_callable($match['target'])) {
  call_user_func_array($match['target'], $match['params']);
} else {
  header("HTTP/1.1 404 Not Found");
  require __DIR__ . "/views/home/error.php";
}
