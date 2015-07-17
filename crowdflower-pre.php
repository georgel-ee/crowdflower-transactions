<?php
/*
# File:    crowdflower-pre.php
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

	// Grab the POST request body and clean up
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
	$uid = intval($json->{'uid'});
	$revAmt = intval($json->{'amount'});
	$adjustAmt = intval($json->{'adjusted_amount'});
	
	// Some verification, and we're good to go!.
	if($signature == $digest)
	{
		// Generate a random conversion ID for CF (we'll need to match this up later)
		$conversionId = uniqid(rand());

		// [!!!] At this point, you'll want to initiate a db query to store the following variables to reconcile later: [!!!]
		// $uid, $revAmt, $adjustAmt and $conversionId.

		// Return 200 and tell CF what the conversion id is.
		header("HTTP/1.0 200 OK");
		echo $conversionId;
	}
	else
	{
		// Hash invalid.
		header("HTTP/1.0 400 Bad Request");
		echo 'Invalid Signature.';
	}
	

?>