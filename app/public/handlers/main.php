<?php
session_start();
if (isset($_SESSION['userId'])) {
    echo "Welcome, {$_SESSION['userName']}!";
} else {
    header("Location: /signin");
}
