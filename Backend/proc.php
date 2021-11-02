<?php
session_start();
require_once ('/home/qtn3/Documents/NewFamJamIT490/vendor/autoload.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//Connect to RabbitMQ
$connection = new AMQPStreamConnection('192.168.194.135', 5672, 'dp75', '1234', 'dp75');
$channel = $connection->channel();
//Consume Message from 'username queue'
$channel->queue_declare('username queue', false, false, false, false);
$callback = function($msg){
    $cread=json_decode($msg->body,true);
    if(count($cread) == 2){
        global $channel;
        $state = 0; //login
        //Publish Message to 'backend queue'
        $channel->queue_declare('backend queue', false, false, false, false);
        $credential = array("username"=>$cread['username'], "password"=>$cread['password'], "state"=>$state);
        $msg = new AMQPMessage(json_encode($credential));
        $channel->basic_publish($msg, '', 'backend queue');
    }
    else{
        global $channel;
        $state = 1; //register
        //Publish Message to 'backend queue'
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