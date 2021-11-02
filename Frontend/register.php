<?php
session_start();
require_once ('/home/qtn3/Documents/NewFamJamIT490/vendor/autoload.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//Connect to RabbitMQ
$connection = new AMQPStreamConnection('192.168.194.241', 5672, 'dp75', '1234', 'dp75');
$channel = $connection->channel();

if(isset($_POST['submit'])){
    //Publish Message to 'username queue'
    $channel->queue_declare('username queue', false, false, false, false);
    $username= !empty($_POST['sign_up_name'])?trim($_POST['sign_up_name']):null;
    $password= !empty($_POST['sign_up_pass'])?trim($_POST['sign_up_pass']):null;
    $passwordHashed= password_hash($password, PASSWORD_BCRYPT);
    $email= !empty($_POST['sign_up_email'])?trim($_POST['sign_up_email']):null;
    $credential = array("username"=>$username, "password"=>$passwordHashed, "email"=>$email);
    $msg = new AMQPMessage(json_encode($credential));
    $channel->basic_publish($msg, '', 'username queue');
}
//Consume Message from 'database register queue'
$channel->queue_declare('database register queue', false, false, false, false);
$callback = function($msg){
  $creadUser=json_decode($msg->body,true);
  if($creadUser['state']==1){ //user existed register
    echo 'Username is already existed!';
  }
  else{ //register is success redirecting user to home page
    header('refresh:5,url: home.html');
    die();
  }
};
$channel->basic_consume('database register queue','',false,true,false,false,$callback);
while($channel->is_consuming()){
    $channel->wait();
}

$channel->close();
$connection->close();

?>
<?php
    require 'register_header.php';
?>
<html>
<body>
    <div class="main">
        <p class="sign" align="center">Sign up</p>
        <form class="form1" action="register.php" method="post">
          <input class="un " type="text" align="center" name="sign_up_name" placeholder="Username">
          <input class="pass" type="password" align="center" name="sign_up_pass" placeholder="Password">
          <input class="pass" type="email" align="center" name="sign_up_email" placeholder="Email">
          <input class="submit" type="submit" name="submit" value="Sign up">
          <br>
          <br>
          <a class="submit" align="center" href="login.php">Sign in</a>
        </form>                
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>