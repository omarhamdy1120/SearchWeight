<?php
include 'config.php';

function findBestCombination($products, $targetWeight, $currentWeight, $currentIndex, $currentCombination, &$bestCombination, &$minMcRetail) {
    $tolerance = 0.0001; // Adjust this value as needed

    if (abs($currentWeight - $targetWeight) <= $tolerance && array_sum(array_column($currentCombination, 'McRetail')) < $minMcRetail) {
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

if (isset($_POST['searchWeight'])) {
    $userInput = floatval($_POST['searchWeight']);

    // Round user input to 0.25 if close
    $roundedInput = round($userInput * 4) / 4;

    $searchWeight = $roundedInput;

    $products = array();
    $sql = "SELECT product_id, ReturnRetail, McRetail, weight FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = array(
                "product_id" => $row["product_id"],
                "ReturnRetail" => $row["ReturnRetail"],
                "McRetail" => floatval($row["McRetail"]),
                "weight" => floatval($row["weight"])
            );
        }
    }

    usort($products, function ($a, $b) {
        $mcDiff = $a['McRetail'] - $b['McRetail'];

        $tolerance = 0.0001; // Adjust this value as needed

        if (abs($mcDiff) < $tolerance) {
            $mcDiff = 0;
        }

        if ($mcDiff != 0) {
            return $mcDiff;
        }

        return $b['weight'] - $a['weight'];
    });

    $bestCombination = array();
    $minMcRetail = INF;
    findBestCombination($products, $searchWeight, 0, 0, array(), $bestCombination, $minMcRetail);

    if (empty($bestCombination)) {
        echo "No product combination found for search weight $searchWeight";
    } else {
        echo "Products that add up to $searchWeight and minimize McRetail:<br>";
        foreach ($bestCombination as $product) {
            echo "ReturnRetail: " . $product["ReturnRetail"] .", Making Charge: ". $product["McRetail"] .", Weight: " . $product["weight"],
            "<br>";
        }
        echo "Sum of McRetail: $minMcRetail";
    }
}

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
