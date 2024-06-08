<?php

class CheckAppointment
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function checkAppointment($date, $time)
    {
        $sql = "SELECT * FROM appointment WHERE date = :date AND time = :time";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $res;
    }
}

$checkAppointment = new CheckAppointment($db->getConnection());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $res = $checkAppointment->checkAppointment($date, $time);
    if (count($res) > 0) {
        echo "Appointment already exists!";
    } else {
        echo "Appointment available!";
    }
}
