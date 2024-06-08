<?php

require_once 'db_conn.php';

$db = new DBConnection('127.0.0.1', 'your_database', 'your_user', 'your_password');
$db->connect();

$name = $_POST['name'];
$prename = $_POST['prename'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$date = $_POST['date'];
$time = $_POST['time'];

$db->insertAppointment($name, $prename, $email, $phone, $address, $date, $time);

echo "Termin wurde erfolgreich eingereicht!";
