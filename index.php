<?php

# Do not print anything with php
error_reporting(0);
ini_set('display_errors', 0);

# Require GSMArena API
# Created by FulgerX2007
require_once 'GsmArenaApi.php';
use FulgerX2007\GsmArena\GsmArenaApi;
$gsm = new GsmArenaApi();

# Query search by string
if (!empty($_GET['query'])) {
	$_GET['query'] = strtolower($_GET['query']);
	if (file_exists('cache/query-' . $_GET['query'] . '.json')) {
		$data = json_decode(file_get_contents('cache/query-' . $_GET['query'] . '.json'), true);
	} else {
		$data = $gsm->search($_GET['query']);
		if($data['status'] != "error") file_put_contents('cache/query-' . $_GET['query'] . '.json', json_encode($data));
	}
}
# Search by slug
elseif (!empty($_GET['slug'])) {
	$_GET['slug'] = strtolower($_GET['slug']);
	if (file_exists('cache/device-' . $_GET['slug'] . '.json')) {
		$data = json_decode(file_get_contents('cache/device-' . $_GET['slug'] . '.json'), true);
	} else {
		$data = $gsm->getDeviceDetail($_GET['slug']);
		if($data['status'] != "error") file_put_contents('cache/device-' . $_GET['slug'] . '.json', json_encode($data));
	}
}
# Search by brands
elseif (!empty($_GET['brands'])) {
	$_GET['brands'] = strtolower($_GET['brands']);
	if (file_exists('cache/brands.json')) {
		$data = json_decode(file_get_contents('cache/brands.json'), true);
	} else {
		$data = $gsm->getBrands();
		if($data['status'] != "error") file_put_contents('cache/brands.json', json_encode($data));
	}
}
# No method found
else {
	$data = ['status' => 'error'];
}

# Finish the request
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode($data, JSON_PRETTY_PRINT);
header('Connection: close');
header('Content-Length: '.ob_get_length());
ob_end_flush();
ob_flush();
flush();