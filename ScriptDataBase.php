<?php
include 'config.php';

function findBestCombination($products, $targetWeight, $currentWeight, $currentIndex, $currentCombination, &$bestCombination, &$minMcRetail) {
    if ($currentWeight == $targetWeight && array_sum(array_column($currentCombination, 'McRetail')) < $minMcRetail) {
        $bestCombination = $currentCombination;
        $minMcRetail = array_sum(array_column($bestCombination, 'McRetail'));
    }

    if ($currentWeight >= $targetWeight || $currentIndex >= count($products)) {
        return;
    }

    for ($quantity = 1; $quantity <= 2; $quantity++) {
        $newWeight = $currentWeight + ($quantity * $products[$currentIndex]["weight"]);
        if ($newWeight <= $targetWeight) {
            $newCombination = $currentCombination;
            for ($i = 1; $i <= $quantity; $i++) {
                $newCombination[] = $products[$currentIndex];
            }
            findBestCombination($products, $targetWeight, $newWeight, $currentIndex + 1, $newCombination, $bestCombination, $minMcRetail);
        }
    }

    findBestCombination($products, $targetWeight, $currentWeight, $currentIndex + 1, $currentCombination, $bestCombination, $minMcRetail);
}

// Check if the form was submitted and the searchWeight is set
if (isset($_POST['searchWeight'])) {
    $searchWeight = floatval($_POST['searchWeight']); // Get the user input as a float value

    // Fetch the products from the database
    $products = array();
    $sql = "SELECT product_id, ReturnRetail, McRetail, weight FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = array(
                "product_id" => $row["product_id"],
                "ReturnRetail" => $row["ReturnRetail"],
                "McRetail" => floatval($row["McRetail"]), // Convert McRetail to float
                "weight" => floatval($row["weight"])
            );
        }
    }

    // Sort the products array in ascending order of McRetail and descending order of weights
    usort($products, function ($a, $b) {
        $mcDiff = $a['McRetail'] - $b['McRetail'];
        if ($mcDiff != 0) {
            return $mcDiff;
        }
        return $b['weight'] - $a['weight'];
    });

    $bestCombination = array();
    $minMcRetail = PHP_INT_MAX;
    findBestCombination($products, $searchWeight, 0, 0, array(), $bestCombination, $minMcRetail);

    // Display the result
    echo "Products that add up to $searchWeight and minimize McRetail:<br>";
    foreach ($bestCombination as $product) {
        echo "ReturnRetail: " . $product["ReturnRetail"] .", Making Charge: ". $product["McRetail"] .", Weight: " . $product["weight"],
        "<br>";
    }

    // Display the sum of McRetail
    echo "Sum of McRetail: $minMcRetail";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Weight</title>
</head>
<body>
    <h1>Enter Search Weight</h1>
    <form method="post" action="">
        <label for="searchWeight">Search Weight:</label>
        <input type="number" step="0.01" name="searchWeight" id="searchWeight" required>
        <button type="submit">Search</button>
    </form>
</body>
</html>
