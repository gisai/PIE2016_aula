<?php
    include("mysqlconfig.php");
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // username and password sent from form

        $formusername = mysqli_real_escape_string($db,$_POST['uname']);
        $formpassword = mysqli_real_escape_string($db,$_POST['psw']);
        $result;

        $sql = "CALL loginsession('$formusername','$formpassword',@res);";
        $result1 = mysqli->query($db,$sql);
        $result2 = mysqli->query($db,"SELECT @res AS res;");
        $row = mysqli_fetch_array($result2,MYSQLI_ASSOC);
        $active = $row['res'];

        $count = mysqli_num_rows($result2);

        //Table Row must be 1
        
    }    
?>
<!DOCTYPE html>
<html>
<style>
h2{
    text-align: center;
    font-size: auto;
    font: 22px/1.5em Arial,Helvetica,sans-serif;
    font-family: Arial, Helvetica, sans-serif;
}
body{
    background-color: #D7E7F4;
    font: 11px/1.5em Arial,Helvetica,sans-serif;
    font-style: normal;
    font-variant-ligatures: normal;
    font-variant-caps: normal;
    font-variant-numeric: normal;
    font-weight: normal;
    font-stretch: normal;
    font-size: 11px;
    line-height: 1.5em;
    font-family: Arial, Helvetica, sans-serif;
}
form {
    margin-left: auto;
    margin-right: auto;
    width: 50%;
    border: 3px solid #f1f1f1;
    background-color: #F0F5FB;
}

input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}



button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
}

.container {
    padding: 16px;
}

span.psw {
    float: right;
    padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
    span.psw {
       display: block;
       float: none;
    }
    .cancelbtn {
       width: 100%;
    }
}
</style>
<body>

<h2>Aula Virtual GISAI</h2>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post">

  <div class="container">
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
        
    <button type="submit">Login</button>
    <input type="checkbox" checked="checked"> Remember me
  </div>
</form>

</body>
</html>