<?php


require 'db.php';
//newTable("users", "username,email,password_hash,ip,try_count,created_at,server_slots,credits");
//newTable("servers", "user_id,name,type,ram,cpu,storage,status,last_started,node_id,created_at");

//deleteTable("nodes");
//ssdeleteTable("nodes1");
//newColumn("users" ,"slots_used");
//deleteColumn("users", "test");
/*insert("nodes", [
    "node_ip" => "yavuz",
    "password" => "hash"
]);*/
//deleteID("users", 1);
//pull("users", 1);
//update("users", "server_slots", 8, 5);
//update("nodes", "node_ip", 1, "yarrak");
//echo password_hash("Ff20092012.", PASSWORD_DEFAULT);

//newTable("admins", "usernames");
//insert("admins", ["usernames" => "yavuzsemih"]);

//newTable("resource_prices", "resource_type,max_amount,perunit_price,billing_period");
//newColumn("resource_prices", "available");
//deleteColumn("resource_prices", "avalible");
//insert("resource_prices", ["resource_type" => "vcpu", "max_amount"=> 6, "perunit_price" => 650, "billing_period" => 30, "available" => true]);

/*insert("resource_prices", [
    "resource_type" => "ram",
    "max_amount" => 32,
    "perunit_price" => 500,
    "billing_period" => 30,
    "available" => true
]);

insert("resource_prices", [
    "resource_type" => "storage",
    "max_amount" => 100,
    "perunit_price" => 20,
    "billing_period" => 30,
    "available" => true
]);

insert("resource_prices", [
    "resource_type" => "backup_slot",
    "max_amount" => 5,
    "perunit_price" => 250,
    "billing_period" => 30,
    "available" => true
]);

insert("resource_prices", [
    "resource_type" => "extra_port",
    "max_amount" => 10,
    "perunit_price" => 50,
    "billing_period" => 30,
    "available" => true
]);

insert("resource_prices", [
    "resource_type" => "minecraft_base",
    "max_amount" => 1,
    "perunit_price" => 350,
    "billing_period" => 30,
    "available" => true
]);

insert("resource_prices", [
    "resource_type" => "discord_bot_base",
    "max_amount" => 1,
    "perunit_price" => 200,
    "billing_period" => 30,
    "available" => true
]);

insert("resource_prices", [
    "resource_type" => "web_server_base",
    "max_amount" => 1,
    "perunit_price" => 250,
    "billing_period" => 30,
    "available" => true
]);*/

//update("users", "credits", 23, 2500);
//update("users", "credits", 24, 2050);

//newColumn('admins', "perm_level");
//newTable("logs", "log_title,date,time,note,status");
//newColumn("logs", "log_info");
//deleteColumn("servers", "node_id");
//newColumn("servers", "uid");
//newColumn("servers", "owner_id");
//insert("servers", ["user_id"=>8, "name"=>"testServer", "type"=> "mc", "ram"=>6, "cpu"=>4, "storage"=>20, "status"=> "active", "last_started"=>"none", "created_at"=> date("Y-m-d H:i:s"), "uid"=> time(), "owner_id"=>8]);
//deleteColumn("servers", "user_id");
//newColumn("servers", "server_ip");
//newColumn("servers", "server_ports");

//update("servers", "server_ip", 0, "37.16.74.139:1327");
//update("servers", "server_ports", 0, "31");
//newColumn("servers", "node");
//update("servers", "node", 0, "vast-node02");
//newColumn("servers", "power");
//update("servers", "power", 0, "on");

//insert("servers", ["owner_id"=>8, "name"=>"testServer", "type"=> "mc", "ram"=>6, "cpu"=>4, "storage"=>20, "status"=> "active", "last_started"=>"none", "created_at"=> date("Y-m-d H:i:s"), "uid"=> time(), "owner_id"=>8, "server_ip"=> "37.16.74.139:1327", "server_ports"=> "31"]);
//update("servers", "power", 1, "on");
//update("servers", "node", 1, "vast-node02");

//update("users", "password_hash", 29, password_hash("kerim59", PASSWORD_DEFAULT));
?> 