<?php
$conn = new mysqli("localhost", "hassisbs_userbs", "Vah,cKTdipQk", "hassisbs_portfolio_bscs");

if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}
?>