rate_script.php

<?php

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $control_no = $_POST['control_no'] ?? null;
    $client_type = isset($_POST['client_type']) ? implode(", ", $_POST['client_type']) : null;
    $date = $_POST['date'] ?? null;
    $sex = isset($_POST['sex']) ? implode(", ", $_POST['sex']) : null;
    $age = $_POST['age'] ?? null;
    $region_of_residence = $_POST['region_of_residence'] ?? null;
    $service_availed = $_POST['service_availed'] ?? null;

    $cc1 = isset($_POST['cc1']) ? implode(", ", $_POST['cc1']) : null;
    $cc2 = isset($_POST['cc2']) ? implode(", ", $_POST['cc2']) : null;
    $cc3 = isset($_POST['cc3']) ? implode(", ", $_POST['cc3']) : null;

    // Insert into database
    try {
        $sql = "INSERT INTO client_feedback (
                    control_no, client_type, date, sex, age, 
                    region_of_residence, service_availed, cc1, cc2, cc3
                ) VALUES (
                    :control_no, :client_type, :date, :sex, :age, 
                    :region_of_residence, :service_availed, :cc1, :cc2, :cc3
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':control_no' => $control_no,
            ':client_type' => $client_type,
            ':date' => $date,
            ':sex' => $sex,
            ':age' => $age,
            ':region_of_residence' => $region_of_residence,
            ':service_availed' => $service_availed,
            ':cc1' => $cc1,
            ':cc2' => $cc2,
            ':cc3' => $cc3,
        ]);

        echo "Feedback successfully saved!";
    } catch (PDOException $e) {
        echo "Error saving feedback: " . $e->getMessage();
    }
}
?>
