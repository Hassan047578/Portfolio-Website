<!DOCTYPE html>
<html>
<head>
<title>Signup</title>

</head>
<style>
    body{
 background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
    /*color:#e2e8f0;*/
}

  
.container{
    width:350px;
    margin:150px auto;
    padding:20px;
    /*background:transparent;*/
    text-align:center;
    box-shadow:0 0 10px purple;
    /*border:1ps solid purple;*/
    border-radius:20px;
}
  input{
    width:90%;
    padding:10px;
    margin:10px;
    border-radius:20px;
    background-color:white;
    /*color:white;*/
    border:1px solid white;
}
.container button{
    width:100px;
    padding:14px;
    margin-top:18px;
    border:none;
    border-radius:30px;
    font-size:16px;
    color:black;
    cursor:pointer;
    
}
.container button:hover{
    background:rgb(222, 41, 222);
    color:white;
}
 .container a{
     display:block;
     margin-top:18px;
     color:black;
     text-decoration:none;
     font-size:15px;
 } 
   .sign{
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
   } 
  #lgn:hover{
    color:#025175
  }
    
    
</style>

<body>


<div class="container">

<h1 id="sign">Signup</h1>

<form action="process/signup_process.php" method="POST">

<input type="text" name="name" placeholder="Name" required><br>

<input type="email" name="email" placeholder="Email" required><br>

<input type="password" name="password" placeholder="Password" required><br>

<button type="submit">Register</button>

</form>

<br>

<a id="lgn" href="login.php">Already have account? Login</a>

</div>

</body>
</html>