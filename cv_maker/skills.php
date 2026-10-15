<?php
session_start();

if (!isset($_SESSION['cv_user'])) {
    $_SESSION['cv_user'] = uniqid();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Skills - CV Maker</title>

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

/* NAV */
nav{
    display:flex;
    justify-content:center;
    gap:20px;
    padding:15px;
    position:sticky;
    top:0;
    backdrop-filter:blur(10px);
    border-bottom:1px solid rgba(255,255,255,0.2);
}

nav a{
    text-decoration:none;
    padding:8px 14px;
    border-radius:8px;
    font-weight:600;
    color:purple;
    border:1px solid purple;
    transition:0.3s;
}

nav a:hover{
    background:purple;
    color:white;
}

/* CONTAINER */
.container{
    width:90%;
    max-width:600px;
    margin:40px auto;
    background:rgba(255,255,255,0.35);
    backdrop-filter:blur(10px);
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* TITLE */
.container h2{
    text-align:center;
    margin-bottom:20px;
    color:purple;
}

/* FORM */
form{
    display:flex;
    flex-direction:column;
}

label{
    margin-top:10px;
    margin-bottom:5px;
    font-weight:600;
}

/* INPUT + SELECT */
input, select{
    padding:10px;
    border-radius:10px;
    border:1px solid rgba(0,0,0,0.2);
    outline:none;
    transition:0.3s;
    background:white;
}

input:focus, select:focus{
    border:1px solid purple;
    box-shadow:0 0 6px rgba(128,0,128,0.4);
}

/* BUTTON */
button{
    margin-top:20px;
    padding:12px;
    border:none;
    border-radius:10px;
    background:purple;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:transparent;
    color:purple;
    border:2px solid purple;
}
</style>

</head>

<body>

<nav>
    <a href="cv.php">View CV</a>
    <a href="bio.php">Bio</a>
    <a href="education.php">Education</a>
    <a href="projects.php">Projects</a>
    <a href="skills.php">Skills</a>
    <!-- <a href="certifications.php">Certifications</a> -->
    <a href="languages.php">Languages</a>
</nav>

<div class="container">

    <h2>Skills</h2>

    <form method="POST" action="save_skills.php">

        <label>Skill Name</label>
        <input type="text" name="skill_name" placeholder="e.g. Python, MS Word">

        <label>Skill Level</label>
        <select name="skill_level">
            <option>Beginner</option>
            <option>Intermediate</option>
            <option>Advanced</option>
        </select>

        <button type="submit">Save & Next</button>

    </form>

</div>

</body>
</html>