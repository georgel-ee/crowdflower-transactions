<?php
/*
# File:    crowdflower-post.php
# Author:  George Lee
# Date:    Created: 9/7/2013, uploaded 7/16/2015
# E-mail:  george@georgel.ee
# Web: http://georgel.ee
# © Copyright 2015 George Lee / georgel.ee. All Rights Reserved.
# Description:
# This file is intended for crowdflower channel partners so their apps can interpret crowdflower transactions meaningfully.
# This script allows a server to initiate Crowdflower transactions and using crowdflower-post.php, receive the
# transaction and store data that transaction data that crowdflower sends back to the server. 
# WARNING: Make changes to code as necessary before using! (indicated by [!!!] in comment)
*/

// Include any globally used scripts here. A database connection at the least would be great!

	// Grab the POST request body
	$request_body = file_get_contents('php://input');
	$decode = urldecode($request_body);
	$result = parse_str($decode,$output);
	$json = json_decode($output['payload']);
	// [!!!] Put your CF channel key here for verification of transaction. [!!!]
	$key = 'YOUR CROWDFLOWER CHANNEL KEY HERE';

	// For verification purposes, generate digest using sha1 hashing.
	$signature = $output['signature'];
	$digest = sha1($output['payload'].''.$key);
	
	// Define variables from json payload
	$conversionId = $json->{'conversion_id'};
	$revenue = intval($json->{'amount'});
	$jobtitle = $json->{'job_title'};		
	$adjustAmt = intval($json->{'adjusted_amount'});
	
	// [!!!] You're not provided the user id this time, so get it from the database by matching the unique conversion id [!!!]
	
	// Validation:
	// [!!!] Make sure that conversion ID only credited user once in database, or else throw an error! [!!!]
	// Change $transactionexists as needed.
	$transactionexists = false;
	if($transactionexists == true)
	{
		header("HTTP/1.0 400 Bad Request");
		echo 'Transaction has already been posted.';
		exit;
	}
	
	if($signature == $digest)	
	{
		// OK, Proceed. Store the transaction in your user table and any other data you want into the database. Change $condition as needed.
		$condition = true;
		if($condition == true)
		{
			echo 'OK';
		}
		else
		{
			header("HTTP/1.0 400 Bad Request");
		}
	}
	else
	{
		// Invalid hash
		echo "Invalid Signature.";
	}
	

?>