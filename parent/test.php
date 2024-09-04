<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bus_reg_system";


$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch learners on the waiting list
$waitingListQuery = "SELECT * FROM busses WHERE waiting_list  = 1";
$waitingListResult = $conn->query($waitingListQuery);

// Fetch learners using bus transport on a specific day
$dailyTransportQuery = "
    SELECT l.*, bt.date, bt.bus_time
    FROM busses bt
    JOIN learner l ON bt.learner_id = l.learner_id
    WHERE bt.date = CURDATE()
";
$dailyTransportResult = $conn->query($dailyTransportQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily MIS Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Daily MIS Report</h1>

    <h2>Learners on the Waiting List</h2>
    <table>
        <tr>
            <th>Waitinglist No</th>
            <th>Learner ID</th>
            <th>Admin ID</th>
            <th>Learner ID</th>
            <th>Learner Name</th>
            <th>Learner Phone</th>
            <th>Waiting List Group</th>
        </tr>
        <?php
        if ($waitingListResult->num_rows > 0) {
            while($row = $waitingListResult->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["waitinglist_no"] . "</td>
                        <td>" . $row["Learner_id"] . "</td>
                        <td>" . $row["admin_id"] . "</td>
                        <td>" . $row["learner_name"] . "</td>
                        <td>" . $row["Learner_phone"] . "</td>
                        <td>" . ($row["waiting_list_group"] ? "Yes" : "No") . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No learners on the waiting list.</td></tr>";
        }
        ?>
    </table>

    <h2>Learners Using Bus Transport Today</h2>
    <table>
        <tr>
            <th>Bus D</th>
            <th>Route ID</th>
            <th>Learner ID</th>
            <th>Capacity</th>
            <th>Bus Spacestatus</th>
            <th>Date</th>
            <th>Bus time</th>
        </tr>
        <?php
        if ($dailyTransportResult->num_rows > 0) {
            while($row = $dailyTransportResult->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["bus_id"] . "</td>
                        <td>" . $row["route_id"] . "</td>
                        <td>" . $row["learner_id"] . "</td>
                        <td>" . $row["capacity"] . "</td>
                        <td>" . $row["bus_spacestatus"] . "</td>
                        <td>" . $row["date"] . "</td>
                        <td>" . $row["bus_time"] . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No learners using bus transport today.</td></tr>";
        }
        ?>
    </table>

    <?php
    // Close connection
    $conn->close();
    ?>
</body>
</html>
