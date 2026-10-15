<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Public Gallery | Hassan Wazir</title>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Segoe UI,sans-serif;
}

body{
background:radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);
min-height:100vh;
}

.container{
width:90%;
max-width:1200px;
margin:auto;
}



header{
padding:20px 0;
}

nav{
display:flex;
justify-content:space-between;
align-items:center;
}

nav ul{
display:flex;
list-style:none;
gap:20px;
}

nav ul li a{
text-decoration:none;
color:black;
font-weight:600;
}

.auth-buttons{
display:flex;
gap:10px;
}

.login-btn{
padding:8px 15px;
border:1px solid purple;
color:purple;
border-radius:8px;
text-decoration:none;
}

.signup-btn{
padding:8px 15px;
background:purple;
color:white;
border-radius:8px;
text-decoration:none;
}



.hero{
text-align:center;
padding:40px 0;
}

.hero h1{
font-size:48px;
}

.hero span{
color:purple;
}

.hero p{
margin-top:10px;
color:#333;
}



.gallery-grid{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
padding-bottom:50px;
}

.card{
background:white;
border-radius:12px;
overflow:hidden;
box-shadow:0 8px 18px rgba(0,0,0,0.08);
transition:0.3s;
}

.card:hover{
transform:translateY(-5px);
}



.image-box{
width:100%;
height:220px;
display:flex;
justify-content:center;
align-items:center;
background:#f8f8f8;
padding:10px;
}

.image-box img{
max-width:100%;
max-height:100%;
width:auto;
height:auto;
object-fit:contain;
display:block;
}



.content{
padding:12px;
}

.content h4{
color:purple;
font-size:16px;
margin-bottom:6px;
}

.content p{
font-size:13px;
color:#444;
min-height:35px;
}

.content small{
color:gray;
font-size:12px;
display:block;
margin-top:8px;
}



footer{
width:500px;
max-width:90%;
margin:20px auto;
background:purple;
color:white;
padding:18px;
border-radius:18px;
text-align:center;
}

footer ul{
display:flex;
justify-content:space-around;
list-style:none;
}



@media(max-width:992px){

.gallery-grid{
grid-template-columns:repeat(2,1fr);
}

}

@media(max-width:576px){

nav{
flex-direction:column;
gap:15px;
}

nav ul{
flex-wrap:wrap;
justify-content:center;
}

.gallery-grid{
grid-template-columns:1fr;
}

.hero h1{
font-size:34px;
}

footer ul{
flex-direction:column;
gap:10px;
}

}

</style>
</head>

<body>

<header>
<div class="container">

<nav>

<ul>
<li><a href="index.php">Home</a></li>
<li><a href="tutorial.php">Tutorial</a></li>
<li><a href="public_gallery.php">Gallery</a></li>
<li><a href="contact.php">Contact</a></li>
</ul>

<div class="auth-buttons">
<a href="login.php" class="login-btn">Login</a>
<a href="signup.php" class="signup-btn">Signup</a>
</div>

</nav>

</div>
</header>

<section class="hero">
<h1>Public <span>Gallery</span></h1>
<p>Only public uploaded images are shown here.</p>
</section>

<div class="container">

<div class="gallery-grid" id="gallerydata"></div>

</div>

<footer>
<ul>
<li>hassanqadri047@gmail.com</li>
<li>Nankana Sahib</li>
</ul>
</footer>

<script>

$(document).ready(function(){

loadPhotos();

function loadPhotos(){

$.ajax({
url:"get_public_photo.php",
type:"GET",
dataType:"json",

success:function(response){

var output = "";

if(response.length > 0){

$.each(response,function(index,row){

output += `
<div class="card">

<div class="image-box">
<img src="image/${row.file}">
</div>

<div class="content">

<h4>${row.username}</h4>

<p>${row.description}</p>

<small>${row.created_at}</small>

</div>

</div>
`;

});

}else{

output = "<h2>No Public Images Found</h2>";

}

$("#gallerydata").html(output);

}

});

}

});

</script>

</body>
</html>