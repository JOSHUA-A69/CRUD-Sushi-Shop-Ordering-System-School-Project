<?php
require_once '../controllers/CustomerController.php';

$customerController = new CustomerController();
$customer = null;

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $customerID = $_GET['id'];
    $customer = $customerController->getProfile($customerID);

    if (!$customer) {
        echo "Customer not found.";
        exit;
    }
} else {
    echo "Customer ID not specified.";
    exit;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data safely
    $firstName = $_POST['firstName'] ?? null;
    $middleInitial = $_POST['middleInitial'] ?? null;
    $lastName = $_POST['lastName'] ?? null;
    $email = $_POST['email'] ?? null;
    $phoneNumber = $_POST['phoneNumber'] ?? null;
    $street = $_POST['street'] ?? null;
    $citytown = $_POST['citytown'] ?? null;
    $houseNumber = $_POST['houseNumber'] ?? null;

    // Update customer profile
    $updateSuccess = $customerController->updateProfile($customerID, $firstName, $middleInitial, $lastName, $email, $phoneNumber, $street, $citytown, $houseNumber);

    if ($updateSuccess) {
        echo "Customer profile updated successfully!";
        // Optionally, redirect to another page here
    } else {
        echo "Failed to update customer profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/manage_customers.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow p-4">
            <h1 class="text-center mb-4">Edit Customer Profile</h1>
            
            <form method="POST" action="" class="needs-validation" novalidate>
                <!-- First Name -->
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" name="firstName" id="firstName" class="form-control" 
                        value="<?= htmlspecialchars($customer['firstName']) ?>" required>
                </div>
                
                <!-- Middle Initial -->
                <div class="mb-3">
                    <label for="middleInitial" class="form-label">Middle Initial</label>
                    <input type="text" name="middleInitial" id="middleInitial" class="form-control" 
                        value="<?= htmlspecialchars($customer['middleInitial']) ?>">
                </div>
                
                <!-- Last Name -->
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" name="lastName" id="lastName" class="form-control" 
                        value="<?= htmlspecialchars($customer['lastName']) ?>" required>
                </div>
                
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" 
                        value="<?= htmlspecialchars($customer['email']) ?>" required>
                </div>
                
                <!-- Phone Number -->
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                    <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" 
                        value="<?= htmlspecialchars($customer['phoneNumber']) ?>">
                </div>
                
                <!-- Street -->
                <div class="mb-3">
                    <label for="street" class="form-label">Street</label>
                    <input type="text" name="street" id="street" class="form-control" 
                        value="<?= htmlspecialchars($customer['street']) ?>">
                </div>
                
                <!-- City/Town -->
                <div class="mb-3">
                    <label for="citytown" class="form-label">City/Town</label>
                    <input type="text" name="citytown" id="citytown" class="form-control" 
                        value="<?= htmlspecialchars($customer['citytown']) ?>">
                </div>
                
                <!-- House Number -->
                <div class="mb-3">
                    <label for="houseNumber" class="form-label">House Number</label>
                    <input type="text" name="houseNumber" id="houseNumber" class="form-control" 
                        value="<?= htmlspecialchars($customer['houseNumber']) ?>">
                </div>
                
                <!-- Submit Button -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="manage_customers.php" class="btn btn-secondary">Back to Customer List</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
