<?php 

function selected($current,$input){
    if($current == $input){
        return "selected";
    }
    return "";
}
function getTests(&$error){
    include __DIR__ . '/../../backend/db.php';

    // Fetch available tests
    $tests = $pdo->query("SELECT test_id, title FROM tests")->fetchAll(PDO::FETCH_ASSOC);
    return $tests;
}

$error = "";
$mesg = "";
$test_id = "";
$question = "";
$optA = "";
$optB = "";
$optC = "";
$optD = "";
$correct = "";
if(isset($_POST['test_id'])){
    include __DIR__ . '/../../backend/db.php';
    $test_id = $_POST['test_id'];
    $question = $_POST['question_text'];
    $optA = $_POST['option_a'];
    $optB = $_POST['option_b'];
    $optC = $_POST['option_c'];
    $optD = $_POST['option_d'];
    $correct = $_POST['correct_option'];

    try {
        $stmt = $pdo->prepare("INSERT INTO questions 
        (test_id, question_text, option_a, option_b, option_c, option_d, correct_option)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$test_id, $question, $optA, $optB, $optC, $optD, $correct]);
    }
    catch(Exception $e){
        $mesg = $e->getMessage();
    }
    $mesg = "Question uploaded successfully!";
}

?>