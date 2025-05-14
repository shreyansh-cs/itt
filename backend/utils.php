<?php 
    
    function getAPIToken($env="stage",&$apiKey,&$apiSecret,&$error)
    {
        include 'db.php';
        $ok = false;
        $sql = "SELECT api_key as API_KEY , api_secret as API_SECRET FROM razorpay where type='$env' and enabled=1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $apiKey = trim($row['API_KEY']); //remove whitespace if any
            $apiSecret = trim($row['API_SECRET']); //remove whitespace if any
            $ok = true;
        }
        else
        {
            $error = "Unable to get API Key and Secret";
        } 
        $conn->close();
        return $ok;
    }
    

    function getAllClasses()
    {
        include 'db.php'; // Make sure this creates a $pdo instance (not $conn) using PDO
        $rows = [];
        try {
            $stmt = $pdo->prepare("SELECT ID, NAME FROM classes WHERE SUPPORTED = 1 ORDER BY ID ASC");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle error or log it
            error_log("Database error: " . $e->getMessage());
        }

        return $rows;
    }

    function getUserDetailsForClass($class,&$error)
    {
        include 'db.php'; // Make sure this creates a $pdo instance (not $conn) using PDO
        $rows = [];
        $sql = "";
        try {
            if(!empty($class)){
                $sql = "SELECT ID as ID, full_name as NAME FROM users where user_class= :class";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':class', $class, PDO::PARAM_INT);    
            }
            else {
                $sql = "SELECT ID as ID, full_name as NAME FROM users";
                $stmt = $pdo->prepare($sql);
            }
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle error or log it
            $error = "Database error: " . $e->getMessage();
        }
        return $rows;
    }

    function getStreamsForClass($class) 
    {
        include 'db.php'; // This should define $pdo using PDO
    
        $rows = [];
        try {
            $stmt = $pdo->prepare("SELECT ID, NAME FROM streams WHERE CLASS_ID = :class");
            $stmt->bindParam(':class', $class, PDO::PARAM_INT);
            $stmt->execute();
    
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    
        return $rows;
    }
    

    function getSubjectsForStream($class, $stream) 
    {
        include 'db.php'; // Assumes $pdo is defined here
    
        $rows = [];
        try {
            $sql = "SELECT ID, NAME 
                    FROM subjects 
                    WHERE ID IN (
                        SELECT SUBJECT_ID 
                        FROM streamubjectmap 
                        WHERE STREAM_ID = :stream
                    )";
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':stream', $stream, PDO::PARAM_INT);
            $stmt->execute();
    
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    
        return $rows;
    }
    

    function getSectionsForSubject($class, $stream, $subject) 
    {
        include 'db.php'; // Should set up $pdo
    
        $rows = [];
        try {
            $sql = "SELECT ID, NAME FROM sections WHERE SUBJECT_ID = :subject";
            //echo $sql; // Optional: keep this for debugging
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':subject', $subject, PDO::PARAM_INT);
            $stmt->execute();
    
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    
        return $rows;
    }    

    function getChaptersForSection($class, $stream, $subject, $section) {
        include 'db.php'; // This should define $pdo
    
        $rows = [];
        try {
            $sql = "SELECT ID, NAME FROM chapters WHERE SECTION_ID = :section";
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':section', $section, PDO::PARAM_INT);
            $stmt->execute();
    
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    
        return $rows;
    }    

    function getNotesForChapter($class, $stream, $subject, $chapter) {
        include 'db.php'; // $pdo should be defined here
    
        $rows = [];
        try {
            $sql = "SELECT ID, NAME, PDF, TEXT 
                    FROM notes 
                    WHERE CHAPTER_ID = :chapter";
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':chapter', $chapter, PDO::PARAM_INT);
            $stmt->execute();
    
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    
        return $rows;
    }    

    function getPDFPathFromNote($note) {
        include 'db.php'; // Assumes $pdo is set up here
    
        $rows = [];
        try {
            $sql = "SELECT PDF, NAME FROM notes WHERE ID = :note";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':note', $note, PDO::PARAM_INT);
            $stmt->execute();
    
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    
        return $rows;
    }
    

    function getVideoForChapter($class, $stream, $subject, $chapter) {
        include 'db.php'; // Assumes $pdo is defined here
    
        $rows = [];
        try {
            $sql = "SELECT ID, NAME, LINK 
                    FROM videos 
                    WHERE CHAPTER_ID = :chapter";
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':chapter', $chapter, PDO::PARAM_INT);
            $stmt->execute();
    
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    
        return $rows;
    }
    

    //Check if this option in dropdown is selected or not
    function checkSelected($thisOptionValue,$selectedValue)
    {
        //must check if there was something selected in last form submit
        if(isset($selectedValue))
        {
            if($thisOptionValue == $selectedValue)
            {
                return "selected";
            }
        }
    }

    function insertNotes($chapter_id, $notes_title, $notesText, $pdf_file_path, &$error) {
        include 'db.php'; // Assumes $pdo is set up here
    
        $ret = false;
        try {
            $sql = "INSERT INTO notes (NAME, TEXT, PDF, CHAPTER_ID) 
                    VALUES (:notes_title, :notesText, :pdf_file_path, :chapter_id)";
    
            $stmt = $pdo->prepare($sql);
            
            // Bind the parameters safely
            $stmt->bindParam(':notes_title', $notes_title, PDO::PARAM_STR);
            $stmt->bindParam(':notesText', $notesText, PDO::PARAM_STR);
            $stmt->bindParam(':pdf_file_path', $pdf_file_path, PDO::PARAM_STR);
            $stmt->bindParam(':chapter_id', $chapter_id, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                $error = "New record created successfully";
                $ret = true;
            } else {
                $error = "Error: " . $stmt->errorInfo()[2]; // More detailed error info
                $ret = false;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            $ret = false;
        }
    
        return $ret;
    }    

    function insertVideo($video_title, $video_link, $chapter_id, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
    
        $ok = false;
        try {
            $sql = "INSERT INTO videos (NAME, LINK, CHAPTER_ID) 
                    VALUES (:video_title, :video_link, :chapter_id)";
    
            $stmt = $pdo->prepare($sql);
            
            // Bind parameters securely
            $stmt->bindParam(':video_title', $video_title, PDO::PARAM_STR);
            $stmt->bindParam(':video_link', $video_link, PDO::PARAM_STR);
            $stmt->bindParam(':chapter_id', $chapter_id, PDO::PARAM_INT);
    
            // Execute the query
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $ok = true;
                } else {
                    $error = "Record not inserted";
                }
            } else {
                $error = "Error: " . $stmt->errorInfo()[2]; // More detailed error message
                $ok = false;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            $ok = false;
        }
    
        return $ok;
    }    

    function deleteNote($noteid, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
    
        $ret = false;
        try {
            $sql = "DELETE FROM notes WHERE ID = :noteid";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':noteid', $noteid, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $error = "Record deleted successfully";
                    $ret = true;
                } else {
                    $error = "No record found to delete";
                }
            } else {
                $error = "Error: " . $stmt->errorInfo()[2]; // Detailed error info
                $ret = false;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            $ret = false;
        }
    
        return $ret;
    }    

    function deleteVideo($videoid, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
    
        $ret = false;
        try {
            $sql = "DELETE FROM videos WHERE ID = :videoid";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':videoid', $videoid, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $error = "Record deleted successfully";
                    $ret = true;
                } else {
                    $error = "No record found to delete";
                }
            } else {
                $error = "Error: " . $stmt->errorInfo()[2]; // Detailed error info
                $ret = false;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            $ret = false;
        }
    
        return $ret;
    }    
        // Function to generate token
    function generateToken($length = 64) 
    {
        return bin2hex(random_bytes($length));  // Generates a secure random token
    }

    function changePassword($user_id,$new_password,&$error)
    {
        include 'db.php';
        $ok = false;
        try 
        {
            $sql = "UPDATE users SET password= :password where ID= :id";
            //$sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            // Start transaction
            $pdo->beginTransaction();
            // Bind and sanitize input
            $stmt->bindParam(':password', $new_password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        
            $stmt->execute();
            // Commit if all good
            $pdo->commit();
            $ok = true;
        } 
        catch (PDOException $e) 
        {
            // Roll back if anything goes wrong
            $pdo->rollBack();
            $error =  "Error: " . $e->getMessage();
            $ok = false;
        }

        return $ok;
    }

    //Both param can be email or phone
    function authUser($email_or_phone, $password, &$row, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
    
        $sql = "SELECT id AS ID, full_name AS FULL_NAME, password AS PASSWORD, user_type AS USER_TYPE, user_class AS USER_CLASS, verified AS VERIFIED, email AS EMAIL, phone AS PHONE 
                FROM users WHERE email = :email_or_phone OR phone = :email_or_phone";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email_or_phone', $email_or_phone, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Check if user is verified
                if ($row['VERIFIED'] == 0) {
                    $error = "UserID is not verified";
                    return false;
                }
    
                // Verify password securely
                //echo $password,$row['PASSWORD'];
                //if (!password_verify($password, $row['PASSWORD'])) {
                if($password != $row['PASSWORD']){
                    $error = "Username / Password not matching";
                    return false;
                }
    
                // Successful login
                return true;
            } elseif ($stmt->rowCount() == 0) {
                $error = "No such user exists";
                return false;
            } else {
                $error = "Unknown error";
                return false;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            return false;
        }
    }    

    function clearExpiredTokens($userid,&$error)
    {
        include 'db.php';
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $db_password);
        $query = "DELETE FROM api_tokens WHERE user_id = ? and expires_at <= NOW()";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$userid]);

        $affectedRows = $stmt->rowCount();
        $error =  "Expired Tokens: " . $affectedRows;
    }

    /*
    function getValidToken($userid,&$token,&$error)
    {
        include 'db.php';
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $db_password);

        // Check token validity
        $query = "SELECT token as TOKEN FROM api_tokens WHERE user_id = ? AND expires_at > NOW()";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$userid]);
        $apiToken = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($apiToken) {
            //echo  "<pre>".print_r($apiToken)."</pre>";
            $token = $apiToken['TOKEN'];// Token is valid, return it
            return true;
        }

        return false;  //token does not exist
    }

    function authenticateByToken($token,&$row,&$error) 
    {
        include 'db.php';

        // Check token validity
        $sql = "SELECT id as ID, expires_at as EXPIRES_AT FROM api_tokens WHERE token = '$token' and expires_at > NOW()";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0)
        {
            //First one if there are multiple rows
            //echo  "<pre>".print_r($row)."</pre>";
            $row = $result->fetch_assoc();
            return true;//valid token
        }
        $error = "Invalid Token";
        return false;
    }
    */

    //Both param can be email or phone
    function doesEmailExist($email, &$username, &$password, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
    
        $sql = "SELECT email AS EMAIL, phone AS PHONE, password AS PASSWORD 
                FROM users WHERE email = :email";
    
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $username = $row['PHONE'];  // Get phone to send email
                $password = $row['PASSWORD']; // Retrieve the hashed password
                return true;
            } else {
                $error = "User does not exist";
                return false;
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            return false;
        }
    }    

    //Both param can be email or phone
    function doesUserAlreadyExist($param1, $param2, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
        $ok = false;
        try {
            // Prepare the first query
            $sql1 = "SELECT email AS EMAIL, phone AS PHONE FROM users WHERE email = :param1 OR phone = :param1";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->bindParam(':param1', $param1, PDO::PARAM_STR);
            $stmt1->execute();
    
            if ($stmt1->rowCount() > 0) {
                $ok = true; // Match found for param1
            }
    
            // If no match for param1, check param2
            if (!$ok) {
                $sql2 = "SELECT email AS EMAIL, phone AS PHONE FROM users WHERE email = :param2 OR phone = :param2";
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->bindParam(':param2', $param2, PDO::PARAM_STR);
                $stmt2->execute();
    
                if ($stmt2->rowCount() > 0) {
                    $ok = true; // Match found for param2
                }
            }
    
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            return false;
        }
        return $ok;
    }
    
    function getPackageDetails($class, &$row, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
    
        $ok = false;
        try {
            // Prepare the query
            $sql = "SELECT ID AS ID, NAME AS NAME, PRICE AS PRICE 
                    FROM packages 
                    WHERE ID = (SELECT PACKAGE_ID FROM classes WHERE ID = :class)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':class', $class, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $ok = true; // If a row is found, set ok to true
            }
    
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            return false;
        }
    
        return $ok;
    } 
    
    function getReceiptsForAdmin($class, $user_id, $status, &$error) {
        include 'db.php'; // Assumes $pdo is defined here

        $rows = [];
        try {

            $sql = "SELECT u.user_class as CLASS,u.full_name as NAME, c.NAME as CLASS_NAME, r.id AS ID, r.user_id AS USER_ID, r.created_on AS CREATED_ON, r.updated_on AS UPDATED_ON, 
                           r.package_id AS PACKAGE_ID, p.NAME AS PACKAGE_NAME, p.PRICE AS PACKAGE_PRICE, 
                           r.status AS STATUS, po.id AS ORDER_ID, po.amount AS AMOUNT 
                    FROM pay_receipts AS r 
                    JOIN packages AS p ON r.package_id = p.ID 
                    JOIN pay_orders AS po ON po.receipt_id = r.id  
                    JOIN users as u ON u.ID = r.user_id 
                    JOIN classes as c ON  c.ID = r.class_id ";

            if(!empty($class) || !empty($user_id) || !empty($status)){
                $sql .= " WHERE ";
            }
            if(!empty($class)){
                $sql .= " u.user_class = :class ";
            }
            
            if(!empty($user_id) && !empty($status)){
                if(!empty($class)){
                    $sql .= " and ";
                }
                $sql .= " r.user_id = :user_id and r.status= :status ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':class', $class, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            }
            else if(!empty($user_id)){
                if(!empty($class)){
                    $sql .= " and ";
                }
                $sql .= " r.user_id = :user_id ";
                $stmt = $pdo->prepare($sql);
                if(!empty($class)){
                    $stmt->bindParam(':class', $class, PDO::PARAM_INT);
                }
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            }
            else if(!empty($status)){
                if(!empty($class)){
                    $sql .= " and ";
                }
                $sql .= " r.status = :status ";
                $stmt = $pdo->prepare($sql);
                if(!empty($class)){
                    $stmt->bindParam(':class', $class, PDO::PARAM_INT);
                }
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            }
            else {
                $stmt = $pdo->prepare($sql);
                if(!empty($class)){
                    $stmt->bindParam(':class', $class, PDO::PARAM_INT);
                }
            }

            // Prepare the query
            //echo $sql;
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $rows[] = $row;
                }
            }
            else
            {
                $error = "No transactions for this selection";
            } 
        } catch (PDOException $e) {
            $error = "Error executing query: " . $e->getMessage();
        }
    
        return $rows;
    } 

    function getReceiptsForThisUser($user_id, &$rows, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
        
        $rows = [];
        $ok = false;
    
        try {
            // Prepare the query
            $sql = "SELECT r.id AS ID, r.user_id AS USER_ID, r.created_on AS CREATED_ON, r.updated_on AS UPDATED_ON, 
                           r.package_id AS PACKAGE_ID, p.NAME AS PACKAGE_NAME, p.PRICE AS PACKAGE_PRICE, 
                           r.status AS STATUS, po.id AS ORDER_ID, po.amount AS AMOUNT 
                    FROM pay_receipts AS r 
                    JOIN packages AS p ON r.package_id = p.ID 
                    JOIN pay_orders AS po ON po.receipt_id = r.id 
                    WHERE r.user_id = :user_id 
                    ORDER BY r.id DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $rows[] = $row;
                }
            } 
            /*
            else {
                $error = "No receipts found for the user.";
            }
            */
            //We have to return success even if there are no previous receipts so that new receipts can be created
            //If we return error then no new receipts can be created
            $ok = true; 
    
        } catch (PDOException $e) {
            $error = "Error executing query: " . $e->getMessage();
        }
    
        return $ok;
    }    

    function createReceipt($user_id, $class, $package_id, &$row, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
    
        $ok = false;
        try {
            // Prepare the query
            $sql = "INSERT INTO pay_receipts (user_id, package_id, status,class_id) 
                    VALUES (:user_id, :package_id, 'initiated', :class_id)";
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':package_id', $package_id, PDO::PARAM_INT);
            $stmt->bindParam(':class_id', $class, PDO::PARAM_INT);
    
            // Execute the query
            if ($stmt->execute()) {
                $ok = true;
                $last_id = $pdo->lastInsertId();  // Get the last inserted ID
                $row['ID'] = $last_id;
            } else {
                $error = "Error executing query.";
            }
    
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    
        return $ok;
    }    

    function createNewOrderInDB($order_id, $order_amount, $order_receipt, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
    
        $ok = false;
        $sql_order = "INSERT INTO pay_orders (id, amount, receipt_id, status) 
                      VALUES (:order_id, :order_amount, :order_receipt, 'created')";
    
        // Start transaction
        $pdo->beginTransaction();
    
        try {
            // Prepare and bind parameters
            $stmt = $pdo->prepare($sql_order);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
            $stmt->bindParam(':order_amount', $order_amount, PDO::PARAM_STR); // assuming it's a decimal
            $stmt->bindParam(':order_receipt', $order_receipt, PDO::PARAM_INT);
    
            // Execute the query
            if (!$stmt->execute()) {
                throw new Exception("Error creating entry into pay_orders table.");
            }
    
            // Commit the transaction if successful
            $pdo->commit();
            $ok = true;
    
        } catch (PDOException $e) {
            // An error occurred, rollback the transaction
            $pdo->rollBack();
            $error = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            // Catch any other errors
            $pdo->rollBack();
            $error = $e->getMessage();
        }
    
        return $ok;
    }    

    function doesTransactionAlreadyExist($payment_id, &$error) {
        include 'db.php'; // Assumes $pdo is defined here
        $ok = false;
    
        try {
            $sql = "SELECT * FROM pay_transactions WHERE id = :payment_id";
            // Prepare the query
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_STR);
            
            // Execute the query
            $stmt->execute();
            
            // Check if any rows are returned
            if ($stmt->rowCount() > 0) {
                $ok = true; // Transaction exists
            }
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    
        return $ok;
    }    

    function savePaymentDetailsDB($arr, &$error)
    {
        include 'db.php'; // Assumes $pdo is defined here
        $ok = false;
        
        // 1. Update status into pay_orders
        // 2. Create entry into pay_transactions
        // 3. Update status in pay_receipts 
        $order = $arr['order'];
        $order_id = $order['id'];
        $order_amount = $order['amount'];
        $order_receipt = $order['receipt'];
        $order_status = $order['status'];

        // Should we reconcile amount, receipt from existing order_id
        $sql_order = "UPDATE pay_orders SET status = :status WHERE id = :order_id";

        // Payment details
        $payment_status = "";
        $payment_merchant_id = "";
        $update_txn = false; // When we don't have transaction details, only orders are updated
        
        if ($arr['payment']['count'] > 0) {
            $update_txn = true;
            $payment = $arr['payment']['items'][0];
            $payment_id = $payment['id'];
            $payment_amount = $payment['amount'];
            $payment_order_id = $payment['order_id'];
            $payment_status = $payment['status'];
            $payment_error_code = $payment['error_code'];
            $payment_merchant_id = $payment['notes']['merchant_order_id'];

            // If transaction already exists, update it
            if (doesTransactionAlreadyExist($payment_id, $error)) {
                $sql_transaction = "UPDATE pay_transactions SET status = :status WHERE id = :payment_id";
            } else {
                $sql_transaction = "INSERT INTO pay_transactions (id, amount, status, order_id, error_code, notes_merchant_order_id)
                                    VALUES (:payment_id, :payment_amount, :payment_status, :payment_order_id, :payment_error_code, :payment_merchant_id)";
            }
        }

        // Set the receipt status based on the order and payment status
        $receipt_status = "unknown";
        if ($order_status == "paid" && $payment_status == "captured") {
            $receipt_status = "success";
        } else if (!empty($payment_status)) {
            $receipt_status = $payment_status;
        } else if (!empty($order_status)) {
            $receipt_status = $order_status;
        }

        $sql_receipt = "UPDATE pay_receipts SET status = :receipt_status WHERE id = :order_receipt";

        // Begin PDO transaction
        $pdo->beginTransaction();

        try {
            // Prepare and execute the order update
            $stmt_order = $pdo->prepare($sql_order);
            $stmt_order->bindParam(':status', $order_status, PDO::PARAM_STR);
            $stmt_order->bindParam(':order_id', $order_id, PDO::PARAM_STR);
            if (!$stmt_order->execute()) {
                throw new Exception("Error updating pay_orders table: " . $sql_order);
            }

            // If there is a transaction, update or insert the transaction
            if ($update_txn) {
                $stmt_transaction = $pdo->prepare($sql_transaction);
                $stmt_transaction->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
                $stmt_transaction->bindParam(':payment_id', $payment_id, PDO::PARAM_STR);
                $stmt_transaction->bindParam(':payment_amount', $payment_amount, PDO::PARAM_STR);
                $stmt_transaction->bindParam(':payment_order_id', $payment_order_id, PDO::PARAM_STR);
                $stmt_transaction->bindParam(':payment_error_code', $payment_error_code, PDO::PARAM_STR);
                $stmt_transaction->bindParam(':payment_merchant_id', $payment_merchant_id, PDO::PARAM_STR);

                if (!$stmt_transaction->execute()) {
                    throw new Exception("Error updating or inserting into pay_transactions table: " . $sql_transaction);
                }
            }

            // Update the receipt status
            $stmt_receipt = $pdo->prepare($sql_receipt);
            $stmt_receipt->bindParam(':receipt_status', $receipt_status, PDO::PARAM_STR);
            $stmt_receipt->bindParam(':order_receipt', $order_receipt, PDO::PARAM_INT);
            if (!$stmt_receipt->execute()) {
                throw new Exception("Error updating pay_receipts table: " . $sql_receipt);
            }

            // If all queries are successful, commit the transaction
            $pdo->commit();
            $ok = true;
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $pdo->rollBack();
            $error = $e->getMessage();

            //+++++++++++++debug
            // $errorMessage = $e->getMessage();  // The message of the exception
            // $errorFile = $e->getFile();        // The file where the exception was thrown
            // $errorLine = $e->getLine();        // The line number where the exception was thrown
            // $stackTrace = $e->getTraceAsString();
            
            // // Display the details
            // echo "Error: $errorMessage in file $errorFile on line $errorLine";
            // echo "SQL: " . $stmt_transaction->queryString;
            // echo "Stack trace:\n$stackTrace";
            //+++++++++++++debug


            $ok = false;
        }

        return $ok;
    }


    include_once 'public_utils.php';
    function doesUserHasSubscription(&$error)
    {
        include 'db.php'; // Assumes $pdo is defined here

        // Get package details
        $package_details = [];
        if (!getPackageDetails(getUserClass(), $package_details, $error)) {
            $error = "Unable to get package details for this class";
            return false;
        }

        // Get user ID
        $user_id = getUserID();
        if (empty($user_id)) {
            $error = "User ID is empty";
            return false;
        }

        // Prepare the SQL query to check for successful transactions
        $sql = "SELECT status FROM pay_receipts WHERE user_id = :user_id AND package_id = :package_id";
        try {
            // Prepare the statement
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':package_id', $package_details['ID'], PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            $ok = false;
            // Loop through the results and check if any status is 'success'
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['status'] == "success") {
                    $ok = true;
                    break;
                }
            }

            if (!$ok) {
                $error = "No matching success transaction for this package";
            }

        } catch (PDOException $e) {
            // Handle error
            $error = "Database Error: " . $e->getMessage();
            return false;
        }

        return $ok;
    }

    function activateUser($user_id, &$error)
    {
        include 'db.php'; // Assumes $pdo is defined here

        $ok = false;

        // Prepare the SQL query for updating the user verification status
        $sql = "UPDATE users SET verified = 1 WHERE ID = :user_id AND verified = 0";

        try {
            // Begin the transaction
            $pdo->beginTransaction();

            // Prepare the statement
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute() === false) {
                throw new Exception("Error updating users table");
            }

            // Commit the transaction
            $pdo->commit();
            $ok = true;
        } catch (PDOException $e) {
            // An error occurred, rollback the transaction
            $pdo->rollBack();
            $error = "Database Error: " . $e->getMessage();
            $ok = false;
        } catch (Exception $e) {
            // Handle other exceptions
            $pdo->rollBack();
            $error = $e->getMessage();
            $ok = false;
        }

        return $ok;
    }


    function verifyRegisterFromEmailLink($verify_key, &$error)
    {
        include 'db.php'; // Assumes $pdo is defined here
        $ok = false;

        // Prepare the SQL query to find the user by verify_key
        $sql = "SELECT ID as ID FROM users WHERE verify_key = :verify_key";

        try {
            // Prepare the statement
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':verify_key', $verify_key, PDO::PARAM_STR);

            // Execute the statement
            $stmt->execute();

            // Check if a user is found
            if ($stmt->rowCount() > 0) {
                // Fetch the user data
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Try to activate the user
                if (activateUser($row['ID'], $error)) {
                    $ok = true;
                } else {
                    $error = "Unable to activate user";
                }
            } else {
                $error = "User can't be verified, Invalid Key";
            }
        } catch (PDOException $e) {
            // Handle errors with PDO
            $error = "Database Error: " . $e->getMessage();
        }

        return $ok;
    }


    function GetUniqueNumber()
    {
        // Get current timestamp in microseconds
        $timestamp = microtime(true);
        // Convert timestamp to a unique number (combining date/time and microseconds)
        $uniqueNumber = str_replace('.', '', $timestamp);
        return $uniqueNumber;
    }

    function validateRegister($class,$name,$father_name,$email,$mobile,$dob,$password,$uploaded_tmp_file,&$error)
    {
        // Validation variables
        $errors = [];

        if(empty($class)){
            $errors[] = "No class selected";
        }

        // Validate name (must be alphabetic and 3-50 characters long)
        if (empty($name)) {
            $errors[] = "Name is required.";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
            $errors[] = "Name must contain only letters and spaces.";
        } elseif (strlen($name) < 3 || strlen($name) > 50) {
            $errors[] = "Name must be between 3 and 50 characters.";
        }

        // Validate father's name (must be alphabetic and 3-50 characters long)
        if (empty($father_name)) {
            $errors[] = "Father's name is required.";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $father_name)) {
            $errors[] = "Father's name must contain only letters and spaces.";
        } elseif (strlen($father_name) < 3 || strlen($father_name) > 50) {
            $errors[] = "Father's name must be between 3 and 50 characters.";
        }

        // Validate email (must be a valid email address)
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Validate mobile number (must be 10 digits long)
        if (empty($mobile)) 
        {
            $errors[] = "Mobile number is required.";
        } elseif (!preg_match("/^\d{10}$/", $mobile)) 
        {
            $errors[] = "Mobile number must be exactly 10 digits.";
        }

        if (empty($dob)) 
        {
            $errors[] = "Date of birth is required.";
        }

        if (empty($password)) 
        {
            $errors[] = "Passowrd is required.";
        }

        if(empty($uploaded_tmp_file)){
            $errors[] = "No files uploaded for photo";
        }

        // If there are errors, display them
        if (!empty($errors)) 
        {
            foreach ($errors as $err) 
            {
                $error .= "<div>" . htmlspecialchars($err) . "</div>";
            }
            return false;
        } else {
            return true;
        }
    }

    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\Exception; 
    function sendWelcomeMail($username, $email,$phone,$password,$verify_key,&$error)
    {
        require '../PHPMailer/src/Exception.php';
        require '..//PHPMailer/src/PHPMailer.php';
        require '../PHPMailer/src/SMTP.php';


        //Include PHPMailer 
        $mail = new PHPMailer(true); 
        $subject = "Welcome to ITT Icon " . htmlspecialchars($username);
        $replyEmail = "support@itticon.in";
        $homepage = "https://itticon.in/itt/frontend/index.php";
        $message = "
        <html>
        <head>
            <title>Welcome to Our Website</title>
        </head>
        <body>
            <h2>Dear " . htmlspecialchars($username) . ",</h2>
            <p>Thank you for registering with us. We're excited to have you on board!</p>
            <p>Please activate your account using this <a href='https://itticon.in/itt/frontend/verifyuser.php?verify_key=$verify_key'>link</a></p>
            <p>Post activation, You can <a href='https://itticon.in/itt/frontend/login.php'>Login</a>, We encourage you to start exploring the amazing courses we offer.</p>
            <p>If you need any help, feel free to reach out to us.</p>
            <div>Please login using below details.</div>
            <h3>UserName:$phone</h3>
            <h3>Password:$password</h3>
            <p>Best regards,<br><a href='$homepage'>Team ITT</a></p>
        </body>
        </html>
        ";
        try 
        {
            $mail->isSMTP(); 
            $mail->Host = 'smtp.hostinger.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = "$replyEmail"; 
            $mail->Password = 'Normaxin@321'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587; 
            $mail->setFrom("$replyEmail", 'ITT Support'); 
            $mail->addAddress($email); 
            $mail->isHTML(true); $mail->Subject = $subject; 
            $mail->Body = $message; 
            $mail->send(); 
            $error =  'Email sent successfully!'; 
            return true;
        } 
        catch (Exception $e) 
        { 
           $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; 
           return false;
        }
    }

    function sendPasswordResetMail($email,$phone,$password,&$error)
    {
        require '../PHPMailer/src/Exception.php';
        require '..//PHPMailer/src/PHPMailer.php';
        require '../PHPMailer/src/SMTP.php';


        //Include PHPMailer 
        $mail = new PHPMailer(true); 
        $subject = "Reset Password";
        $replyEmail = "support@itticon.in";
        $base = "https://itticon.in/";
        $homepage = $base."itt/frontend/index.php";
        $loginpage = $base."itt/frontend/login.php";
        $message = "
        <html>
        <head>
            <title>Welcome to Our Website</title>
        </head>
        <body>
            <div>Please <a href='$loginpage'>login</a> using below details.</div>
            <h3>UserName:$phone</h3>
            <h3>Password:$password</h3>
            <p>Best regards,<br><a href='$homepage'>Team ITT</a></p>
        </body>
        </html>
        ";
        try 
        {
            $mail->isSMTP(); 
            $mail->Host = 'smtp.hostinger.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = "$replyEmail"; 
            $mail->Password = 'Normaxin@321'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587; 
            $mail->setFrom("$replyEmail", 'ITT Support'); 
            $mail->addAddress($email); 
            $mail->isHTML(true); $mail->Subject = $subject; 
            $mail->Body = $message; 
            $mail->send(); 
            $error =  'Email sent successfully!'; 
            return true;
        } 
        catch (Exception $e) 
        { 
           $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; 
           return false;
        }
    }

    function getStatusColor($status)
    {
        if($status == "success")
        {
            return "success";
        }

        if($status == "failed")
        {
            return "danger";
        }

        return "warning";
    }

?>