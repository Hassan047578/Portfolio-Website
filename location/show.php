<?php
include "config.php";

session_start();
$user = $_SESSION['user_id'];

$data = mysqli_query(
    $conn,
    "SELECT * FROM my_favourite_place WHERE user_id='$user'"
);

$places = [];

while ($row = mysqli_fetch_assoc($data)) {
    $places[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Favourite Places</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
body{
    font-family: Arial;
    background:#f4f7fb;
    text-align:center;
}

.card{
    background:white;
    margin:10px auto;
    padding:12px;
    width:90%;
    max-width:500px;
    border-radius:10px;
    box-shadow:0 0 10px #ccc;
    text-align:left;
}

.delete-btn{
    background:red;
    color:white;
    border:none;
    padding:6px 10px;
    cursor:pointer;
}

#map{
    height:400px;
    width:90%;
    max-width:700px;
    margin:auto;
    border-radius:10px;
    border:1px solid #ddd;
}
</style>
</head>

<body>

<h2>📍 My Favourite Places</h2>

<?php if(count($places) == 0){ ?>

    <p>No places saved yet</p>

<?php } else { ?>

    <?php foreach($places as $p){ ?>

        <div class="card">
            <b>📌 <?php echo $p['place_name']; ?></b><br>
            <small>
                Lat: <?php echo $p['location_latitude']; ?> |
                Lng: <?php echo $p['location_longitude']; ?>
            </small>
            <form method="POST" action="delete.php" onsubmit="return confirm('Delete this place?')">
                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                <button class="delete-btn">Delete</button>
            </form>
        </div>

    <?php } ?>

<?php } ?>

<div id="map"></div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

    // MAP
    var map = L.map('map').setView([30,70], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
    .addTo(map);

    var places = <?php echo json_encode($places); ?>;
    if(places.length > 0){
        var bounds = [];
        for(let i=0; i<places.length; i++){
            let p = places[i];
            let lat = parseFloat(p.location_latitude);
            let lng = parseFloat(p.location_longitude);
            let marker = L.marker([lat,lng]).addTo(map);
            marker.bindPopup(p.place_name);
            bounds.push([lat,lng]);
        }
        map.fitBounds(bounds);
    }

</script>

<br>
<a href="location.php">⬅ Back</a>

</body>
</html>