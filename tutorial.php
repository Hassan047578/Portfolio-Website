// <?php
// session_start();
// if(!isset($_SESSION['user'])){
//     header("Location: login.php");
//     exit;
// }
// ?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Tutorials</title>

<style>
*{
    margin:0;
    padding:0;
    /* box-sizing:border-box; */
    font-family:Arial, sans-serif;
}

body{
     background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
     
    color:white;
}


.container{
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    /* justify-content: center; */
    gap: 50px;
    width:98vw;
    /* max-width:850px; */
    /* margin:50px auto; */
}


h1{
    text-align:center;
    margin-bottom:30px;
    color:purple;
}

iframe{
    width: 300px;
    height: 200px;
    margin-left: 70px;
}
button{
    /* display: block; */
    position: absolute;
    top: -17px;
    right: 50px;
    text-align: center;
    margin-left: 20px;
    width: 100px;
    height: 30px;
    margin-top: 30px;
    background-color: rgb(249, 159, 249);
    color:white;
    border: none;
    border-radius: 20px;
    
}
button:hover{
    background-color: white;
    color: black;
}
a{
    text-decoration: none;
    color: purple;
}

</style>

</head>

<body>
    <button><a href="index.php" class="btn"> Home</a></button>

<h1>📚 My Tutorials</h1>
<div class="container">


<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/rp41BM5Q9wE"
title="Frontend HTML Basics"
frameborder="0"
allowfullscreen>
</iframe>



<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/iTfEAN9XN0Y"
title="Declaration of variable in PHP"
frameborder="0"
allowfullscreen>
</iframe>



<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/EdfhvK_-yvY"
title="University frontend Page"
frameborder="0"
allowfullscreen>
</iframe>



<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/jA6HmIsYeo8"
title="To Get Data from frontend and display on backend"
frameborder="0"
allowfullscreen>
</iframe>

<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/x7uIpkR3u0U"
title="How to upload pictures in backend"
frameborder="0"
allowfullscreen>
</iframe>

<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/xDGBLJWMQ_k"
title="How to connect database with PHP file"
frameborder="0"
allowfullscreen>
</iframe>

<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/Mz9mzGcmV7g"
title="How to fetch data and store it in an array"
frameborder="0"
allowfullscreen>
</iframe>

<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/wLrpVMAlu6A"
title="Facebook login page"
frameborder="0"
allowfullscreen>
</iframe>

<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/zyPQ_UQFRDk"
title="Insert and display picture in database"
frameborder="0"
allowfullscreen>
</iframe>

<iframe 
width="560" 
height="315"
src="https://www.youtube.com/embed/5UxCWW-r1nw"
title="YouTube video player"
frameborder="0"
allowfullscreen>
</iframe>

<iframe 
width="560" 
height="315" 
src="https://www.youtube.com/embed/uwEfTIlPreA" 
title="YouTube video player" 
frameborder="0" 
allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
allowfullscreen>
</iframe>





</div>


</body>
</html>