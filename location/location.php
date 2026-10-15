<?php include "config.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hassan Wazir | My Favourite Place</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
        color: #e2e8f0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .container {
        width: 90%;
        max-width: 1100px;
        margin: auto;
        color: black;
    }

    header {
        position: sticky;
        top: 0;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        z-index: 1000;
    }

    nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        font-size: x-large;
        color: black;
    }

    nav ul {
        display: flex;
        list-style: none;
        gap: 22px;
        align-items: center;
    }

    nav ul li a {
        color: #0a121c;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
    }

    nav ul li a:hover {
        color: #2ca8dd;
    }

    .auth-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .auth-buttons a {
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }

    .login-btn {
        border: 1px solid #e838f8;
        color: #e838f8;
    }

    .signup-btn {
        background: #e838f8;
        color: white;
    }

    .makecv-btn {
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        background: purple;
        color: white !important;
        border: 2px solid purple;
        font-size: 15px;
        transition: 0.3s;
    }

    .makecv-btn:hover {
        background: transparent;
        color: purple !important;
    }

    /* Page Section */
    .page-section {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 40px 0 30px;
    }

    .map-box {
        width: 100%;
        max-width: 620px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        padding: 25px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 8px 32px rgba(128, 0, 128, 0.15);
        color: black;
    }

    .map-box h2 {
        font-size: 22px;
        color: black;
        margin-bottom: 18px;
        font-weight: 700;
        text-align: center;
    }

    .map-box h2 span {
        color: purple;
    }

    .search-row {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
    }

    .search-row input[type="text"] {
        flex: 1;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid rgba(128, 0, 128, 0.3);
        background: rgba(255, 255, 255, 0.8);
        color: black;
        font-size: 14px;
        outline: none;
        transition: border 0.3s;
    }

    .search-row input[type="text"]:focus {
        border-color: purple;
    }

    .search-row input::placeholder {
        color: #888;
    }

    .search-row button {
        padding: 10px 18px;
        border: 2px solid purple;
        background: purple;
        color: white;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        font-size: 14px;
        transition: 0.3s;
    }

    .search-row button:hover {
        background: transparent;
        color: purple;
    }

    #suggestions {
        text-align: left;
        font-size: 13px;
        color: #444;
        margin-bottom: 8px;
    }

    #map {
        height: 350px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(128, 0, 128, 0.2);
        margin-bottom: 15px;
        z-index: 1;
    }

    .save-btn {
        display: block;
        width: 100%;
        padding: 11px;
        border: 2px solid purple;
        background: purple;
        color: white;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        transition: 0.3s;
        text-align: center;
    }

    .save-btn:hover {
        background: transparent;
        color: purple;
    }

    footer {
        width: 500px;
        border: 1px solid purple;
        border-radius: 20px;
        text-align: center;
        padding: 15px 20px;
        background: purple;
        color: white;
        display: flex;
        justify-content: center;
        margin: auto;
        margin-top: 10px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    footer ul {
        display: flex;
        list-style: none;
        gap: 40px;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    footer ul li {
        word-break: break-word;
        text-align: center;
        font-size: 14px;
    }
</style>
</head>

<body>

    <header>
        <div class="container">
            <nav>
                <ul id="menu">
                    <li><a href="../index.php">Home</a></li>
                    <!--<li><a href="tutorial.php">Tutorials</a></li>-->
                    <!--<li><a href="public_gallery.php">Gallery</a></li>-->
                    <!--<li><a href="#projects">Projects</a></li>-->
                    <!--<li><a href="#contact">Contact</a></li>-->
                    <!--<li><a href="QR.php">QR</a></li>-->
                    <!--<li><a href="chatting/index.php">Chat</a></li>-->
                    <!--<li><a href="location/location.php">Location</a></li>-->
                </ul>
                <div class="auth-buttons">
                    <a href="cv_maker/bio.php" class="makecv-btn">Make CV</a>
                    <a href="login.php" class="login-btn">Login</a>
                    <a href="signup.php" class="signup-btn">Signup</a>
                </div>
            </nav>
        </div>
    </header>

    <section class="page-section">
        <div class="map-box">
            <h2> My Favourite <span>Place</span></h2>

            <div class="search-row">
                <input type="text" id="search" placeholder="Search location...">
                <button onclick="search()">Search</button>
            </div>

            <div id="suggestions"></div>

            <form method="POST" action="save.php">
                <input type="hidden" id="name" name="place_name">
                <input type="hidden" id="lat" name="lat">
                <input type="hidden" id="lng" name="lng">

                <div id="map"></div>

                <button type="submit" class="save-btn"> Save Location</button>
            </form>
        </div>
    </section>

    <footer>
        <ul>
            <li>hassanqadri047@gmail.com</li>
            <li>Nankana Sahib</li>
        </ul>
    </footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    var map = L.map('map').setView([31.5204, 74.3587], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    var marker;

    map.on("click", function(e){
        setMarker(e.latlng.lat, e.latlng.lng, "Selected");
    });

    function setMarker(lat, lng, name){
        document.getElementById("lat").value = lat;
        document.getElementById("lng").value = lng;
        document.getElementById("name").value = name;

        if(marker){
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }

        marker.bindPopup(name).openPopup();
    }

    function search(){
        let q = document.getElementById("search").value;
        fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + q)
        .then(res => res.json())
        .then(data => {
            if(data.length == 0){
                alert("Not found");
                return;
            }
            let loc = data[0];
            setMarker(loc.lat, loc.lon, loc.display_name);
            map.setView([loc.lat, loc.lon], 12);
        });
    }
</script>

</body>
</html>