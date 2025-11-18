<?php
// get_business.php
// Returns HTML snippet for a single business by id

include('includes/db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo "<p>Invalid request.</p>";
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT b.*, c.category_name, u.full_name AS owner_name
                        FROM businesses b
                        LEFT JOIN categories c ON b.category_id = c.category_id
                        LEFT JOIN users u ON b.owner_id = u.user_id
                        WHERE b.business_id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $row = $res->fetch_assoc()) {
    // sanitize output
    $name = htmlspecialchars($row['name']);
    $owner = htmlspecialchars($row['owner_name'] ?? 'N/A');
    $category = htmlspecialchars($row['category_name'] ?? 'N/A');
    $email = htmlspecialchars($row['email'] ?? 'N/A');
    $phone = htmlspecialchars($row['phone'] ?? 'N/A');
    $website = htmlspecialchars($row['website'] ? $row['website'] : 'N/A');
    $address = htmlspecialchars($row['address'] ?? '');
    $city = htmlspecialchars($row['city'] ?? '');
    $state = htmlspecialchars($row['state'] ?? '');
    $description = nl2br(htmlspecialchars($row['description'] ?? 'No description provided.'));
    $verified = $row['is_verified'] ? 'Yes' : 'No';
    $status = htmlspecialchars($row['status']);
    $created = htmlspecialchars($row['created_at']);

    echo "<h3>{$name}</h3>";
    echo "<p><strong>Owner:</strong> {$owner}</p>";
    echo "<p><strong>Category:</strong> {$category}</p>";
    echo "<p><strong>Email:</strong> {$email}</p>";
    echo "<p><strong>Phone:</strong> {$phone}</p>";
    if ($website !== 'N/A') {
        $url = htmlspecialchars($row['website']);
        echo "<p><strong>Website:</strong> <a href=\"{$url}\" target=\"_blank\">{$url}</a></p>";
    } else {
        echo "<p><strong>Website:</strong> N/A</p>";
    }
    echo "<p><strong>Address:</strong> {$address}";
    if ($city || $state) echo ", {$city}" . ($state ? ", {$state}" : "");
    echo "</p>";
    echo "<hr>";
    echo "<p><strong>Description:</strong><br>{$description}</p>";
    echo "<hr>";
    echo "<p><strong>Verified:</strong> {$verified} &nbsp; • &nbsp; <strong>Status:</strong> {$status}</p>";
    echo "<p class='text-muted small'>Added: {$created}</p>";
} else {
    http_response_code(404);
    echo "<p>Business not found.</p>";
}

$stmt->close();
$conn->close();
