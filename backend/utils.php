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
        include 'db.php';
        $rows = [];
        //Select list of supported classes
        $sql = "SELECT ID AS ID, NAME as NAME FROM classes where SUPPORTED=1 ORDER BY ID ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        $conn->close();
        return $rows; 
    }
    function getStreamsForClass($class)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID AS ID, NAME as NAME FROM streams where CLASS_ID=$class";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        $conn->close();
        return $rows;
    }

    function getSubjectsForStream($class,$stream)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID as ID, NAME as NAME from subjects where ID IN(SELECT SUBJECT_ID FROM streamubjectmap where STREAM_ID=$stream)";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        $conn->close();
        return $rows;
    }

    function getSectionsForSubject($class,$stream,$subject)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID as ID, NAME as NAME from sections where SUBJECT_ID=$subject";
        echo $sql;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        $conn->close();
        return $rows;
    }

    function getChaptersForSection($class,$stream,$subject,$section)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID AS ID, NAME as NAME FROM chapters where SECTION_ID=$section";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        $conn->close();
        return $rows;
    }

    function getNotesForChapter($class,$stream,$subject,$chapter)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID AS ID, NAME as NAME, PDF AS PDF, TEXT AS TEXT FROM notes where CHAPTER_ID=$chapter";
        //echo $sql;
        $result = $conn->query($sql);
        if ( $result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        }
        $conn->close(); 
        return $rows; 
    }

    function getPDFPathFromNote($note)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT PDF AS PDF, NAME AS NAME FROM notes where ID=$note";
        //echo $sql;
        $result = $conn->query($sql);
        if ( $result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        $conn->close();
        return $rows; 
    }

    function getVideoForChapter($class,$stream,$subject,$chapter)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID AS ID, NAME as NAME, LINK AS LINK FROM videos where CHAPTER_ID=$chapter";
        //echo $sql;
        $result = $conn->query($sql);
        if ( $result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        }
        $conn->close(); 
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

    function insertNotes($chapter_id,$notes_title,$notesText,$pdf_file_path,&$error/*OUT*/ )
    {
        include 'db.php';
        $ret = false;
        $sql = "INSERT INTO notes(NAME,TEXT,PDF,CHAPTER_ID) VALUES ('$notes_title','$notesText', '$pdf_file_path',$chapter_id)";
        // Execute the query
        if ($conn->query($sql) === TRUE) 
        {
            $error = "New record created successfully";
            $ret = true;
        } 
        else 
        {
            $error = "Error: " . $sql . $conn->error;
            $ret = false;
        } 
        $conn->close();
        return $ret;
    }

    function insertVideo($video_title,$video_link,$chapter_id,&$error)
    {
        include 'db.php';
        $ok = false;
        $sql = "INSERT INTO videos(NAME,LINK,CHAPTER_ID) VALUES ('$video_title','$video_link',$chapter_id)";
        if ($conn->query($sql) === TRUE) 
        {
            if($conn->affected_rows > 0)
            {
                $ok = true;
            }
            else
            {
                $error = "Record not inserted";
            }
        } 
        else 
        {
            $error = "Error: " . $sql . $conn->error;
            $ok = false;
        } 
        $conn->close();
        return $ok; 
    }

    function deleteNote($noteid,&$error)
    {
        include 'db.php';
        $ret = false;
        $sql = "delete from notes where ID=$noteid";
        if ($conn->query($sql) === TRUE) 
        {
            $error = "Record deleted successfully";
            if($conn->affected_rows == 0)
            {
                $error = "No record updated"; 
            }

            $ret = true;
        } 
        else 
        {
            $error = "Error: " . $sql . $conn->error;
            $ret = false;
        } 
        $conn->close();
        return $ret;
    }

    function deleteVideo($videoid,&$error)
    {
        include 'db.php';
        $ret = false;
        $sql = "delete from videos where ID=$videoid";
        if ($conn->query($sql) === TRUE) 
        {
            $error = "Record deleted successfully";
            if($conn->affected_rows == 0)
            {
                $error = "No record updated"; 
            }

            $ret = true;
        } 
        else 
        {
            $error = "Error: " . $sql . $conn->error;
            $ret = false;
        } 
        $conn->close();
        return $ret;
    }

        // Function to generate token
    function generateToken($length = 64) 
    {
        return bin2hex(random_bytes($length));  // Generates a secure random token
    }

    //Both param can be email or phone
    function authUser($email_or_phone,$password,&$row/*OUT*/,&$error)
    {
        include 'db.php';
        $sql = "SELECT id as ID, full_name as FULL_NAME, password as PASSWORD, user_type as USER_TYPE, user_class as USER_CLASS, verified AS VERIFIED, email as EMAIL, phone as PHONE from users where email='$email_or_phone' OR phone='$email_or_phone'";
        //echo $sql;
        $ok = false;
        $result = $conn->query($sql);
        if ($result && $result->num_rows == 1) 
        {
            //only one row of user
            $row = $result->fetch_assoc();
            //echo '<pre>'; print_r($row); echo '</pre>';
            $ok = true;
        }
        else if($result && $result->num_rows == 0)
        {
            $error = "No such user exists"; 
        }
        else
        {
            $error = "Unknown Error ". $conn->error;
        }

        if($ok)
        {
            $ok=true; //by default matched
            if($row['VERIFIED'] == 0)
            {
                $error = "UserID is not verified"; 
                $ok=false;
            }
            
            if($ok && $row['PASSWORD'] != $password)
            {
                $error = "Username / Password not matching"; 
                $ok = false;
            }
            //Generate token
            if($ok)
            {
                // Password is correct, generate token
                //Check if a token already exists
                $token = "";
                clearExpiredTokens($row['ID'],$error); //clear expired tokens
                if(getValidToken($row['ID'],$token,$error))
                {
                    $row['TOKEN']=$token; //return the token
                }
                else
                {
                    $token = generateToken();

                    // Save token in the database
                    $userId = $row['ID'];
                    $expiry_interval = "1";//1 hour
                    $sql = "INSERT INTO api_tokens (user_id, token, expires_at) VALUES ($userId, '$token', ADDDATE(NOW(), INTERVAL {$expiry_interval} HOUR))";
                    $result = $conn->query($sql);
                    if($result && $conn->affected_rows > 0)
                    {
                        $row['TOKEN']=$token; //return the token
                    }
                    else
                    {
                        $ok=false;
                        $error = "Unable to generate new token";
                    }
                }
            }
        }
        $conn->close();
        return $ok;
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

    //Both param can be email or phone
    function doesEmailExist($email,&$username,&$password,&$error)
    {
        include 'db.php';
        $sql = "SELECT email as EMAIL, phone as PHONE, password as PASSWORD from users where email='$email'";
        //echo $sql;
        $ok = false;
        $result = $conn->query($sql);
        if ( $result && $result->num_rows > 0) 
        {
                $ok = true;
                $row = $result->fetch_assoc();
                $username = $row['PHONE'];//get phone to send email
                $password = $row['PASSWORD'];

        }
        else
        {
            $error = "User does not exist";
        } 
        $conn->close();   
        return $ok;
    }

    //Both param can be email or phone
    function doesUserAlreadyExist($param1,$param2,&$error)
    {
        include 'db.php';
        $rows = [];
        $sql1 = "SELECT email as EMAIL, phone as PHONE from users where email='$param1' OR phone='$param1'";
        $sql2 = "SELECT email as EMAIL, phone as PHONE from users where email='$param2' OR phone='$param2'";
        $ok = false;
        $result = $conn->query($sql1);
        if ( $result && $result->num_rows > 0) 
        {
                $ok = true;
        }
        
        //param 1 did not match, check for param2
        if(!$ok)
        {
            $result = $conn->query($sql2);
            if ( $result && $result->num_rows > 0) 
            {
                    $ok = true;
            }
        }
        $conn->close();
        return $ok;
    }

    function getPackageDetails($class,&$row,&$error)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT ID as ID, NAME as NAME, PRICE as PRICE from packages where ID = (SELECT PACKAGE_ID as PACKAGE_ID from classes where ID=$class)";
        //echo $sql;
        $ok = false;
        $result = $conn->query($sql);
        if ( $result && $result->num_rows > 0) 
        {
                $row = $result->fetch_assoc();
                $ok = true;
        }
        $conn->close();
        return $ok;
    }

    function getReceiptsForThisUser($user_id,&$rows,&$error)
    {
        include 'db.php';
        $rows = [];
        $sql = "SELECT r.id AS ID, r.user_id as USER_ID, r.created_on as CREATED_ON, r.updated_on as UPDATED_ON, r.package_id as PACKAGE_ID, p.NAME as PACKAGE_NAME, p.PRICE as PACKAGE_PRICE, r.status as STATUS, po.id as ORDER_ID, po.amount as AMOUNT FROM pay_receipts AS r ,packages AS p, pay_orders as po WHERE r.package_id = p.ID and po.receipt_id=r.id and r.user_id = $user_id ORDER BY r.id DESC";
        $result = $conn->query($sql);

        $ok = true;
        if($result == false)
        {
            $ok = false;
            $error = "Error executing - ".$sql;
        }


        if ($ok && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($rows,$row);
            }
        } 
        $conn->close();
        return $ok;  
    }

    function createReceipt($user_id,$package_id,&$row,&$error)
    {
        include 'db.php';
        $ok = false;
        $sql = "INSERT INTO pay_receipts(user_id,package_id,status) VALUES ($user_id,$package_id,'initiated')";
        // Execute the query
        if ($conn->query($sql) === TRUE) 
        {
            $ok = true;
            $last_id = $conn->insert_id;//last inserted id
            $row['ID'] = $last_id;
        } 
        else 
        {
            $error = "Error: " . $sql . $conn->error;
            $ok = false;
        } 
        $conn->close();
        return $ok;
    }

    function createNewOrderInDB($order_id,$order_amount,$order_receipt,&$error)
    {
        include 'db.php';
        $ok = false;
        $sql_order = "INSERT into pay_orders(id,amount,receipt_id,status) values('$order_id',$order_amount,$order_receipt,'created')";

        //echo $sql_order;

        $conn->begin_transaction();

        try
        {
            if ($conn->query($sql_order) === FALSE) {
                throw new Exception("Error creating entry into pay_orders table".$sql_order);
            }
            $conn->commit();
            $ok = true;
        }
        catch (Exception $e) 
        {
            // An error occurred, rollback the transaction
            $conn->rollback();
            $error = $e->getMessage();
            $ok = false;
        }
        $conn->close();
        return $ok;

    }

    function doesTransactionAlreadyExist($payment_id,&$error)
    {
        include 'db.php';
        $rows = [];
        $sql = "select * from pay_transactions where id='$payment_id'";
        $ok = false;
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) 
        {
            //$row = $result->fetch_assoc();
            $ok = true;
        }
        $conn->close();
        return $ok;
    }

    function savePaymentDetailsDB($arr,&$error)
    {
        include 'db.php';
        $ok = false;
        //1. update status into pay_orders
        //2. Create entry into pay_transactions
        //3. update status in pay_receipts 
        $order = $arr['order'];
        $order_id = $order['id'];
        $order_amount = $order['amount'];
        $order_receipt = $order['receipt'];
        $order_status = $order['status'];

        //Should we reconcile amount, receipt from existing order_id

        $sql_order = "UPDATE pay_orders set status='$order_status' where id = '$order_id'";
        //(id,amount,receipt_id,status) values('$order_id',$order_amount,$order_receipt,'$order_status')";

        //There was no trasaction for order
        $payment_status = "";
        $payment_merchant_id = "";
        $update_txn = false; // when we don't have transaction details, only orders is what we have
        if($arr['payment']['count'] > 0)
        {
            $update_txn = true;
            $payment = $arr['payment']['items'][0];
            $payment_id = $payment['id'];
            $payment_amount = $payment['amount'];
            $payment_order_id = $payment['order_id'];
            $payment_status = $payment['status'];
            $payment_error_code = $payment['error_code'];
            $payment_merchant_id = $payment['notes']['merchant_order_id'];

            //If it already exist update it's status
            if(doesTransactionAlreadyExist($payment_id,$error))
            {
                $sql_transaction = "UPDATE pay_transactions set status='$payment_status' where id='$payment_id'";
            }
            else
            {
                $sql_transaction = "INSERT into pay_transactions(id,amount,status,order_id,error_code,notes_merchant_order_id) values('$payment_id',$payment_amount,'$payment_status','$payment_order_id','$payment_error_code',$payment_merchant_id)";
            }
        }

        $receipt_status = "unknown";
        
        //success
        if($order_status == "paid" && $payment_status == "captured")
        {
            $receipt_status = "success";
        }
        else if(!empty($payment_status))
        {
            $receipt_status = $payment_status; 
        }
        else if(!empty($order_status))
        {
            $receipt_status = $order_status; 
        }
        else
        {
            //unlikely to have this case
        }

        $sql_receipt = "UPDATE pay_receipts set status='$receipt_status' WHERE id = $order_receipt";

        $conn->begin_transaction();

        try
        {
            if ($conn->query($sql_order) === FALSE) {
                throw new Exception("Error updating pay_orders table".$sql_order);
            }

            if ($update_txn && $conn->query($sql_transaction) === FALSE) {
                throw new Exception("Error updating pay_transactions table".$sql_transaction);
            }

            if ($conn->query($sql_receipt) === FALSE) {
                throw new Exception("Error updating pay_receipts table".$sql_receipt);
            }
        
            // If everything is successful, commit the transaction
            $conn->commit();
            $ok = true;
        }
        catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            $error = $e->getMessage();
            $ok = false;
        }
        $conn->close();
        return $ok;
    }

    include_once 'public_utils.php';
    function doesUserHasSubscription(&$error)
    {
        include 'db.php';
        $package_details = [];
        if(!getPackageDetails(getUserClass(),$package_details,$error))
        {
            $error = "Unable to get package details for this class";
            return false;
        }

        $user_id = getUserID();
        if(empty($user_id))
        {
            $error = "user id is empty";
            return false;
        }

        //Try to get all receipts for this package id and see if any of them status is success or not
        $sql = "SELECT status as STATUS from pay_receipts WHERE user_id={$user_id} AND package_id={$package_details['ID']}";
        
        $result = $conn->query($sql);

        $ok = false;
        if(!$result || $result->num_rows <= 0)
        {
            $error = "No valid packages in receipts";
            return false;
        }

        while($row = $result->fetch_assoc()) 
        {
            //check if any row is success
            if($row['STATUS'] == "success")
            {
                $ok = true;
                break;
            }
        }

        if($ok == false)
        {
            $error = "No matching success trxn for this package";
        }

        $conn->close();
        return $ok;  
    }

    function activateUser($user_id,&$error)
    {
        include 'db.php';
        $ok = false;

        //TODO - set verified=1 when final rollout is done
        $sql = "UPDATE users set verified=1 WHERE ID = $user_id and verified=0";
        $conn->begin_transaction();
        try
        {
            if ($conn->query($sql) === FALSE) {
                throw new Exception("Error updating users table - ".$sql);
            }        
            // If everything is successful, commit the transaction
            $conn->commit();
            $ok = true;
        }
        catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            $error = $e->getMessage();
            $ok = false;
        }
        $conn->close();
        return $ok;
    }

    function verifyRegisterFromEmailLink($verify_key,&$error)
    {
        include 'db.php';
        $ok = false;
        $sql = "SELECT ID as ID from users WHERE verify_key='$verify_key'";
        //echo $sql;
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            if(activateUser($row['ID'],$error))
            {
                $ok = true;
            }
            else
            {
                $error = "Unable to activate user";
            }
        } 
        else
        {
            $error = "User can't be varied, Invalid Key";
        }
        $conn->close();
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

    function validateRegister($name,$father_name,$email,$mobile,&$error)
    {
        // Validation variables
        $errors = [];

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

        // If there are errors, display them
        if (!empty($errors)) 
        {
            $error = "<ul>";
            foreach ($errors as $err) 
            {
                $error .= "<li>" . htmlspecialchars($err) . "</li>";
            }
            $error .= "</ul>";
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
        $replyEmail = "support@itticon.site";
        $homepage = "https://itticon.site/itt/frontend/index.php";
        $message = "
        <html>
        <head>
            <title>Welcome to Our Website</title>
        </head>
        <body>
            <h2>Dear " . htmlspecialchars($username) . ",</h2>
            <p>Thank you for registering with us. We're excited to have you on board!</p>
            <p>Please activate your account using this <a href='https://itticon.site/itt/frontend/verifyuser.php?verify_key=$verify_key'>link</a></p>
            <p>Post activation, You can <a href='https://itticon.site/itt/frontend/login.php'>Login</a>, We encourage you to start exploring the amazing courses we offer.</p>
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
        $replyEmail = "support@itticon.site";
        $base = "https://itticon.site/";
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
            return "green";
        }

        if($status == "failed")
        {
            return "red";
        }

        return "yellow";
    }

?>