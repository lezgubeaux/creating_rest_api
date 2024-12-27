<?php
// Ensures that responses are sent in JSON format.
header("Content-Type: application/json");
// connect to db
include 'db.php';

// Captures the HTTP method (GET, POST, PUT, DELETE).
$method = $_SERVER['REQUEST_METHOD'];
// Retrieves the raw input data
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        handlePost($pdo, $input);
        break;
    case 'PUT':
        handlePut($pdo, $input);
        break;
    case 'DELETE':
        handleDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleGet($pdo)
{
    // Fetches all rows from the users table.
    $sql = "SELECT * FROM users";
    $stmt = $pdo->prepare($sql);
    // run query
    $stmt->execute();
    // fetch all as  associative array
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}

function handlePost($pdo, $input)
{

    if (isset($input['name']) && isset($input['email'])) {
        // Inserts a new user into the users table.
        // Uses named placeholders (:name, :email) to prevent SQL injection.
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
        // placeholders are replaced by actual values at runtime using parameter binding
        $stmt = $pdo->prepare($sql);
        // run query
        $stmt->execute(['name' => $input['name'], 'email' => $input['email']]);
        echo json_encode(['message' => 'User created successfully']);
    } else {
        echo json_encode(['message' => 'The submitted data was not valid!']);
    }
}

function handlePut($pdo, $input)
{
    if (isset($input['name']) && isset($input['email']) && isset($input['id'])) {
        // Updates an existing user in the users table based on the id.
        // Uses named placeholders (:name, :email) to prevent SQL injection.
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        // placeholders are replaced by actual values at runtime using parameter binding
        $stmt = $pdo->prepare($sql);
        // run query
        $stmt->execute(['name' => $input['name'], 'email' => $input['email'], 'id' => $input['id']]);
        // check if the change took place (id exists)
        if ($stmt->rowCount() === 0) {
            echo "No rows were updated. The ID may not exist.";
        } else {
            echo json_encode(['message' => 'User updated successfully']);
        }
    } else {
        echo json_encode(['message' => 'The submitted data was not valid!']);
    }
}

function handleDelete($pdo, $input)
{
    if (isset($input['id'])) {
        // deletes an existing user in the users table based on the id.
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        // run query
        $stmt->execute(['id' => $input['id']]);
        // check if the change took place (id exists)
        if ($stmt->rowCount() === 0) {
            echo "No rows were deleted. The ID may not exist.";
        } else {
            echo json_encode(['message' => 'User deleted successfully']);
        }
    } else {
        echo json_encode(['message' => 'The submitted data was not valid!']);
    }
}
