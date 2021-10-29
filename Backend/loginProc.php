<?php
session_start();
require_once ('/home/qtn3/Documents/NewFamJamIT490/vendor/autoload.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


if(isset($_POST['submit'])){
    //Connect to RabbitMQ
    $connection = new AMQPStreamConnection('192.168.194.150', 5672, 'dp75', '1234', 'dp75');
    $channel = $connection->channel();

    //Publish Message
    $channel->queue_declare('username queue', false, false, false, false);
    $username= !empty($_POST['user_name'])?trim($_POST['user_name']):null;
    $password= !empty($_POST['pass_word'])?trim($_POST['pass_word']):null;
    $credential = array("username"=>$username, "password"=>$password);
    $msg = new AMQPMessage(json_encode($credential));
    $channel->basic_publish($msg, '', 'username queue');

    //Consume Message
    $channel->queue_declare('signal queue', false, false, false, false);
    $callback = function($msg){
        $cread=json_decode($msg->body,true);
        // $_SESSION['signalSignup'] = $cread['signal'];
        $signal = "true"; //$cread['signal'];
        if($signal == "true"){
        // header('location:localhost.html');
        echo "<h1>Directing to hompage</h1>";
        }
        else{
            echo "Account existed!";
            header('location:signup.php');
        }
    };
    $channel->basic_consume('signal queue','',false,true,false,false,$callback);
    while($channel->is_consuming()){
        $channel->wait();
    }

    $channel->close();
    $connection->close();
}
?>