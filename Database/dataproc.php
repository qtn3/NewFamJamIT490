<?php
session_start();
require_once ('/home/qtn3/Desktop/FamJam4/vendor/autoload.php');
require 'create_table.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//Connect to RabbitMQ
$connection = new AMQPStreamConnection('192.168.194.135', 5672, 'dp75', '1234', 'dp75');
$channel = $connection->channel();
//Consume Message from 'backend queue'
$channel->queue_declare('backend queue', false, false, false, false);
$callback = function($msg){
    $creadUser=json_decode($msg->body,true);
    //Access elements in the array 
    $creadUserName=$creadUser['username'];
    $creadUserPassword=$creadUser['password'];
    $creadUserEmail=$creadUser['email'];
    //Using 'username' to search for the user in the database 
    $sqlQ = "Select * From users Where username='$creadUserName'";
    $prepare=$conn->query($sqlQ);
    
    if(count($creadUser)==3){ //login username, password, state
        global $prepare, $channel, $conn;
        if($prepare){ //user existed
            $state = 1; 
            $channel->queue_declare('database login queue', false, false, false, false);
            $credentialUser = array("username"=>$creadUser['username'], "password"=>$creadUser['password'], "state"=>$state);
            $msg = new AMQPMessage(json_encode($credentialUser));
            $channel->basic_publish($msg, '', 'database login queue');
            $channel->queue_delete('backend queue');// delete backend queue
        }
        else{ //user not existed
            $state = 0; 
            $channel->queue_declare('database login queue', false, false, false, false);
            $credentialUser = array("username"=>$creadUser['username'], "password"=>$creadUser['password'], "state"=>$state);
            $msg = new AMQPMessage(json_encode($credentialUser));
            $channel->basic_publish($msg, '', 'database login queue');
            $channel->queue_delete('backend queue');
        }
    }
    else{ //register username, password, email, state
        global $prepare, $channel, $conn;
        if($prepare){ //user existed
            $state = 1; 
            $channel->queue_declare('database register queue', false, false, false, false);
            $credentialUser = array("username"=>$creadUser['username'], "password"=>$creadUser['password'], "email"=>$creadUser['email'], "state"=>$state);
            $msg = new AMQPMessage(json_encode($credentialUser));
            $channel->basic_publish($msg, '', 'database register queue');
            $channel->queue_delete('backend queue');
        }
        else{ //user not existed

            $sql= "INSERT INTO users (username, password, email) VALUES ('$creadUserName', '$creadUserPassword', '$creadUserEmail')";
            $prepare=$conn->query($sql);
            if($prepare){
                $state = 0; 
                $channel->queue_declare('database register queue', false, false, false, false);
                $credentialUser = array("username"=>$creadUser['username'], "password"=>$creadUser['password'], "email"=>$creadUser['email'], "state"=>$state);
                $msg = new AMQPMessage(json_encode($credentialUser));
                $channel->basic_publish($msg, '', 'database register queue');
                $channel->queue_delete('backend queue');
            }
            else{
                echo 'Insertion is not successful!';
            }
            
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