<?php
if (isset($_POST['balance'])) {
  session_start();
  $_SESSION['credit_balance'] = $_POST['balance'];
}
?>
