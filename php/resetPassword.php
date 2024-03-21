<?php  
    require_once('../php/connectdb.php');
    echo 'resetPassword php is running';
    $newPassword=$_POST['admin-passwordReset'];
    echo "$newPassword";

    if (!empty($newPassword)) {
        if (strlen($newPassword) >= 8 && preg_match('/[A-Za-z]/', $newPassword) && preg_match('/[0-9]/', $newPassword) && preg_match('/[!@#$%^&*()-_+=]/', $newPassword)) {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePasswordStmt = $db->prepare("UPDATE customer SET Password = ? WHERE Username = ?");
            $updatePasswordStmt->execute([$hashedNewPassword, $username]);
            if ($updatePasswordStmt->execute([$hashedNewPassword, $username])) {
                jsAlert('Account and password updated successfully.', true, 4000);
                $updatedSuccessfully = true;
                
            }
        } else {
            jsAlert('New password must be at least 8 characters long and include a number.', false, 10000);
        }
    } elseif ($updatedSuccessfully) {
        jsAlert('Account updated successfully.', true, 4000);
    }
?>