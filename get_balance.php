<?php
// Simulate fetching the credit balance from the database
session_start();
echo isset($_SESSION['credit_balance']) ? $_SESSION['credit_balance'] : 10;
?>
