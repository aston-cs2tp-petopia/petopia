<?php
function jsAlert($message, $success, $duration) {
    //Variables for colours based on the success
    $backgroundColor = $success ? '#d4edda' : '#f8d7da';
    $textColor = $success ? '#155724' : '#721c24';
    $borderColor = $success ? '#c3e6cb' : '#f5c6cb';

    //Returns JS alert
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var messageDiv = document.createElement('div');
                messageDiv.textContent = '".addslashes($message)."';
                messageDiv.style.position = 'fixed';
                messageDiv.style.top = '50%';
                messageDiv.style.left = '50%';
                messageDiv.style.transform = 'translate(-50%, -50%)';
                messageDiv.style.backgroundColor = '$backgroundColor';
                messageDiv.style.color = '$textColor';
                messageDiv.style.padding = '20px';
                messageDiv.style.border = '1px solid $borderColor';
                messageDiv.style.borderRadius = '5px';
                messageDiv.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                messageDiv.style.zIndex = '1000';
                document.body.appendChild(messageDiv);
                
                setTimeout(function() {
                    document.body.removeChild(messageDiv);
                }, $duration); // Message will disappear after specified duration
            });
        </script>";
}
?>