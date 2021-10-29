<?php
session_start();
require_once ('/home/qtn3/Documents/NewFamJamIT490/vendor/autoload.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//Consume Message
$channel->queue_declare('username queue', false, false, false, false);
$callback = function($msg){
    $cread=json_decode($msg->body,true);
    if(count($cread) == 2){
        $state = 0; //login
        $channel->queue_declare('backend queue', false, false, false, false);
        $credential = array("username"=>$cread['username'], "password"=>$cread['password'], "state"=>$state);
        $msg = new AMQPMessage(json_encode($credential));
        $channel->basic_publish($msg, '', 'backend queue');
    }
    else{
        $state = 1; //register
        $channel->queue_declare('backend queue', false, false, false, false);
        $credential = array("username"=>$cread['username'], "password"=>$cread['password'], "email"=>$cread['email'], "state"=>$state);
        $msg = new AMQPMessage(json_encode($credential));
        $channel->basic_publish($msg, '', 'backend queue');
    }
};
$channel->basic_consume('username queue','',false,true,false,false,$callback);
while($channel->is_consuming()){
    $channel->wait();
}

    $channel->close();
    $connection->close();
?>