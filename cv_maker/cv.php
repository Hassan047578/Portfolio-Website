<?php
session_start();
include "../config.php";
include "authentication.php";

if (!isset($_SESSION['cv_user'])) {
    $_SESSION['cv_user'] = uniqid();
}

$user_id = $_SESSION['cv_user'];

$bio    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bio WHERE user_id = '$user_id'"));
$edu    = mysqli_query($conn, "SELECT * FROM education WHERE user_id = '$user_id'");
$proj   = mysqli_query($conn, "SELECT * FROM projects WHERE user_id = '$user_id'");
$skills = mysqli_query($conn, "SELECT * FROM skills WHERE user_id = '$user_id'");
// $certs  = mysqli_query($conn, "SELECT * FROM certifications WHERE user_id = '$user_id'");
$langs  = mysqli_query($conn, "SELECT * FROM languages WHERE user_id = '$user_id'");
?>

<!DOCTYPE html>
<html>
<head>
<title>My CV</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);
    color:#111;
}


nav{
    display:flex;
    justify-content:center;
    gap:20px;
    padding:15px;
    backdrop-filter:blur(10px);
    position:sticky;
    top:0;
    border-bottom:1px solid rgba(255,255,255,0.2);
}

nav a, nav button{
    text-decoration:none;
    padding:8px 14px;
    border-radius:8px;
    font-weight:600;
    border:1px solid purple;
    background:transparent;
    color:purple;
    cursor:pointer;
    transition:0.3s;
}

nav a:hover, nav button:hover{
    background:purple;
    color:white;
}


.container{
    width:90%;
    max-width:1000px;
    margin:40px auto;
}


.cv-header{
    text-align:center;
    padding:30px;
    background:rgba(255,255,255,0.4);
    border-radius:15px;
    backdrop-filter:blur(10px);
    margin-bottom:20px;
}

.cv-header h1{
    font-size:40px;
    color:purple;
}

.cv-header p{
    margin-top:5px;
    color:#222;
}


.cv-section{
    margin-top:20px;
    padding:20px;
    background:rgba(255,255,255,0.35);
    border-radius:15px;
    backdrop-filter:blur(10px);
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

.cv-section h2{
    color:purple;
    margin-bottom:10px;
    border-left:4px solid purple;
    padding-left:10px;
}


.cv-entry{
    padding:10px;
    margin-bottom:10px;
    border-bottom:1px solid rgba(0,0,0,0.1);
}

.cv-entry b{
    font-size:16px;
}

.cv-entry span{
    float:right;
    color:#444;
    font-size:14px;
}


.skills-row{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}

.skill-tag{
    background:purple;
    color:white;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
}


a{
    color:purple;
    font-weight:bold;
}
@media print {

    
    nav,
    button {
        display: none !important;
    }

    
    body {
        background: white !important;
    }

    
    .container {
        box-shadow: none !important;
        background: white !important;
    }

    .cv-header,
    .cv-section {
        background: transparent !important;
        backdrop-filter: none !important;
    }

    
    .cv-header h1,
    .cv-section h2 {
        color: purple !important;
    }

    .skill-tag {
        background: purple !important;
        color: white !important;
    }

    
    .cv-section {
        page-break-inside: avoid;
    }

}
</style>

</head>

<body>

<nav>
    <a href="../index.php">Home</a>
    <a href="cv.php">CV</a>
    <a href="bio.php">Bio</a>
    <a href="education.php">Education</a>
    <a href="projects.php">Projects</a>
    <a href="skills.php">Skills</a>
    <!-- <a href="certifications.php">Certifications</a> -->
    <a href="languages.php">Languages</a>
    <button onclick="window.print()">Download PDF</button>
</nav>

<div class="container">

<?php if ($bio) { ?>
<div class="cv-header">
    <h1><?php echo $bio['full_name']; ?></h1>
    <p><?php echo $bio['job_title']; ?></p>
    <p><?php echo $bio['email']; ?> | <?php echo $bio['phone']; ?> | <?php echo $bio['city']; ?></p>
    <p><?php echo $bio['summary']; ?></p>
</div>
<?php } else { ?>
    <p style="text-align:center;">No CV data found. <a href="bio.php">Start Here</a></p>
<?php } ?>

<?php if (mysqli_num_rows($edu) > 0) { ?>
<div class="cv-section">
    <h2>Education</h2>
    <?php while ($row = mysqli_fetch_assoc($edu)) { ?>
    <div class="cv-entry">
        <b><?php echo $row['qualification']; ?></b> — <?php echo $row['institute']; ?>
        <span><?php echo $row['passing_year']; ?></span>
        <p><?php echo $row['board']; ?> | <?php echo $row['obtained_marks']; ?>/<?php echo $row['total_marks']; ?></p>
    </div>
    <?php } ?>
</div>
<?php } ?>

<?php if (mysqli_num_rows($proj) > 0) { ?>
<div class="cv-section">
    <h2>Projects</h2>
    <?php while ($row = mysqli_fetch_assoc($proj)) { ?>
    <div class="cv-entry">
        <b><?php echo $row['title']; ?></b> (<?php echo $row['year']; ?>)
        <p><?php echo $row['technologies']; ?></p>
        <p><?php echo $row['description']; ?></p>
    </div>
    <?php } ?>
</div>
<?php } ?>

<?php if (mysqli_num_rows($skills) > 0) { ?>
<div class="cv-section">
    <h2>Skills</h2>
    <div class="skills-row">
    <?php while ($row = mysqli_fetch_assoc($skills)) { ?>
        <span class="skill-tag">
            <?php echo $row['skill_name']; ?> (<?php echo $row['skill_level']; ?>)
        </span>
    <?php } ?>
    </div>
</div>
<?php } ?>



<?php if (mysqli_num_rows($langs) > 0) { ?>
<div class="cv-section">
    <h2>Languages</h2>
    <div class="skills-row">
    <?php while ($row = mysqli_fetch_assoc($langs)) { ?>
        <span class="skill-tag">
            <?php echo $row['language_name']; ?> (<?php echo $row['proficiency']; ?>)
        </span>
    <?php } ?>
    </div>
</div>
<?php } ?>

</div>

</body>
</html>