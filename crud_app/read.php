<?php
$id = $_GET["id"];

// Check existence of id parameter before processing further
if (isset($id) && !empty(trim($id))) {
    // Include config file
    require_once "config.php";

    // Enable exceptions for MySQLi (for try / catch)
    // !!! exceptions enabled before mysqli instance is created (within config.php)

    try {

        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = ?";

        $stmt = $mysqli->prepare($sql);

        // Bind variables to the prepared statement as parameters
        // i == integer // params are bound by reference (so can be defined later)
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = trim($id);

        // Explicitly execute the statement
        $stmt->execute();

        // Attempt to execute the prepared statement
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop
            $row = $result->fetch_array(MYSQLI_ASSOC);

            // Retrieve individual field value
            $name = $row["name"];
            $address = $row["address"];
            $salary = $row["salary"];
        } else {
            // URL doesn't contain valid id parameter. Redirect to error page
            header("location: error.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        error_log($e->getMessage()); // Log error details
        echo "A database error occurred. Please try again later.";
    }

    // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <div class="form-group">
                        <label>Name</label>
                        <p><b><?php echo $row["name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <p><b><?php echo $row["address"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Salary</label>
                        <p><b><?php echo $row["salary"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>