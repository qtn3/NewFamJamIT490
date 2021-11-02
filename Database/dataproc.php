<?php
session_start();
require_once ('/home/qtn3/Desktop/FamJam4/vendor/autoload.php');
require 'create_db.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//Connect to RabbitMQ
$connection = new AMQPStreamConnection('192.168.194.135', 5672, 'dp75', '1234', 'dp75');
$channel = $connection->channel();
//Consume Message from 'backend queue'
$channel->queue_declare('backend queue', false, false, false, false);
$callback = function($msg){
    $creadUser=json_decode($msg->body,true);

    //plz write command to search database for user here

    if(count($creadUser)==3){ //login 
        if(true){ //user existed
            $state = 1; 
            $channel->queue_declare('database login queue', false, false, false, false);
            $credentialUser = array("username"=>$creadUser['username'], "password"=>$creadUser['password'], "state"=>$state);
            $msg = new AMQPMessage(json_encode($credentialUser));
            $channel->basic_publish($msg, '', 'database login queue');
        }
        else{ //user not existed
            $state = 0; 
            $channel->queue_declare('database login queue', false, false, false, false);
            $credentialUser = array("username"=>$creadUser['username'], "password"=>$creadUser['password'], "state"=>$state);
            $msg = new AMQPMessage(json_encode($credentialUser));
            $channel->basic_publish($msg, '', 'database login queue');
        }
    }
    else{ //register
        if(true){ //user existed
            $state = 1; 
            $channel->queue_declare('database register queue', false, false, false, false);
            $credentialUser = array("username"=>$creadUser['username'], "password"=>$creadUser['password'], "email"=>$creadUser['email'], "state"=>$state);
            $msg = new AMQPMessage(json_encode($credentialUser));
            $channel->basic_publish($msg, '', 'database register queue');
        }
        else{ //user not existed

            //PLZ PUT COMMAND TO INSERT USER TO THE DATABASE THEN ...

            $state = 0; 
            $channel->queue_declare('database register queue', false, false, false, false);
            $credentialUser = array("username"=>$creadUser['username'], "password"=>$creadUser['password'], "email"=>$creadUser['email'], "state"=>$state);
            $msg = new AMQPMessage(json_encode($credentialUser));
            $channel->basic_publish($msg, '', 'database register queue');
        }
    }
};
$channel->basic_consume('backend queue','',false,true,false,false,$callback);
while($channel->is_consuming()){
    $channel->wait();
}

    $channel->close();
    $connection->close();
?>