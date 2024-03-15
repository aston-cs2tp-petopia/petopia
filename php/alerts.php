<?php
function jsAlert($message, $success, $duration) {
    $backgroundColor = $success ? '#d4edda' : '#f8d7da';
    $textColor = $success ? '#155724' : '#721c24';
    $borderColor = $success ? '#c3e6cb' : '#f5c6cb';

    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var messageDiv = document.createElement('div');
                var closeBtn = document.createElement('span');
                closeBtn.innerHTML = '&times;';
                closeBtn.style.position = 'absolute';
                closeBtn.style.top = '0';
                closeBtn.style.right = '10px';
                closeBtn.style.fontWeight = 'bold';
                closeBtn.style.fontSize = '20px';
                closeBtn.style.cursor = 'pointer';
                
                messageDiv.innerHTML = `".addslashes($message)."`;
                messageDiv.appendChild(closeBtn);
                messageDiv.style.position = 'fixed';
                messageDiv.style.top = '50%';
                messageDiv.style.left = '50%';
                messageDiv.style.transform = 'translate(-50%, -50%)';
                messageDiv.style.backgroundColor = '$backgroundColor';
                messageDiv.style.color = '$textColor';
                messageDiv.style.padding = '20px';
                messageDiv.style.paddingRight = '35px';
                messageDiv.style.border = '1px solid $borderColor';
                messageDiv.style.borderRadius = '5px';
                messageDiv.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                messageDiv.style.zIndex = '1000';
                document.body.appendChild(messageDiv);
                
                closeBtn.onclick = function() {
                    messageDiv.remove();
                };

                setTimeout(function() {
                    if (document.body.contains(messageDiv)) {
                        document.body.removeChild(messageDiv);
                    }
                }, $duration);
            });
        </script>";
}
?>
