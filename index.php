<?php
session_start();
$html = file_get_contents('index.html');

// The exact login row to replace
$loginRow = '<td colspan="4" align="right"><a href="login.html" class="login-link">Login</a></td>';
if (isset($_SESSION["user"])) {
    $user = htmlspecialchars($_SESSION['user']['Pno_Clt'] . ' ' . $_SESSION['user']['No_Clt']);
    $replace = '<td colspan="4" align="right"><span class="user-welcome">Welcome, ' . $user . '</span> <a href="logout.php" class="logout-link">Logout</a></td>';
} else {
    $replace = $loginRow;
}
$html = str_replace($loginRow, $replace, $html);
echo $html;
?>