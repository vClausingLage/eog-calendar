<?php

require_once 'db_conn.php';

$db = new DBConnection('127.0.0.1', 'your_database', 'your_user', 'your_password');
$db->connect();

$appointments = $db->getAppointments(1);

echo "<table>";
echo "<tr><th>Name</th><th>Prename</th><th>Email</th><th>Phone</th><th>Address</th><th>Date</th><th>Time</th></tr>";
foreach ($appointments as $appointment) {
    echo "<tr>";
    echo "<td>" . $appointment['name'] . "</td>";
    echo "<td>" . $appointment['prename'] . "</td>";
    echo "<td>" . $appointment['email'] . "</td>";
    echo "<td>" . $appointment['phone'] . "</td>";
    echo "<td>" . $appointment['address'] . "</td>";
    echo "<td>" . $appointment['date'] . "</td>";
    echo "<td>" . $appointment['time'] . "</td>";
    echo "</tr>";
}
echo "</table>";
