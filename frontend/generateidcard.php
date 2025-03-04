<?php
// dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$stmt = $conn->prepare("SELECT full_name, father_name, dob, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($full_name, $father_name, $dob, $photo);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - I.T.T Group of Education</title>
    <link rel="stylesheet" href="styles.css">
    <style>
      .id-card {
          width: 350px;
          margin: 30px auto;
          padding: 20px;
          border: 2px solid #002060;
          border-radius: 10px;
          text-align: center;
          background: #fff;
      }
      .id-card img {
          width: 120px;
          height: 120px;
          border-radius: 50%;
          margin-bottom: 15px;
      }
      .id-card p {
          font-size: 16px;
          margin: 8px 0;
      }
      .id-card h2 {
          color: #002060;
          margin-bottom: 10px;
      }
      a.logout {
          display: inline-block;
          margin-top: 20px;
          padding: 10px 20px;
          background: #004080;
          color: #fff;
          border-radius: 5px;
          text-decoration: none;
      }
      a.logout:hover {
          background: #002060;
      }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $full_name; ?></h1>
    <div class="id-card">
        <h2>ID Card</h2>
        <img src="<?php echo $photo; ?>" alt="User Photo">
        <p><strong>Name:</strong> <?php echo $full_name; ?></p>
        <p><strong>Father's Name:</strong> <?php echo $father_name; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $dob; ?></p>
    </div>
    <a href="logout.php" class="logout">Logout</a>
</body>
</html>
