<?php
if(isset($_SESSION["notification"])){
    $active = $_SESSION["notification"]["active"] ?? false;

    if (!$active) {
        return;
    }

    $type = $_SESSION["notification"]["type"] ?? "error";
    $message = $_SESSION["notification"]["message"] ?? "ERREUR!!!!!!";
    $icon = ($type === 'success') ? "✔" : "✖";

    echo '
    <div id="notification" class="notification '.$type.'">
        <div class="notification-content">
            <span class="icon">'.$icon.'</span>
            <span class="message">'.$message.'</span>
        </div>
        <div class="progress-bar"></div>
    </div>';

    unset($_SESSION["notification"]);
}
