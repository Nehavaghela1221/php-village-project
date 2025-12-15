<?php
$conn = new mysqli("localhost", "root", "", "devinapura");
if ($conn->connect_error) {
  die("DB connection failed");
}
