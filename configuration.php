<?php

use DTS\eBaySDK\Credentials\CredentialsProvider;

$config['sandbox']['credentials'] = [
	CredentialsProvider::ENV_APP_ID => 'SlawaPid-SearchHe-SBX-7dbc19de5-45bcbcc9',
	CredentialsProvider::ENV_DEV_ID => '63e7a23e-a574-4452-a204-77d938f845b2',
	CredentialsProvider::ENV_CERT_ID => 'SBX-dbc19de5199b-27b0-4dd4-b941-1f3c',
];
$config['production']['credentials'] = [
	CredentialsProvider::ENV_APP_ID => 'SlawaPid-SearchHe-PRD-d69dbd521-eb745828',
	CredentialsProvider::ENV_DEV_ID => '63e7a23e-a574-4452-a204-77d938f845b2',
	CredentialsProvider::ENV_CERT_ID => 'PRD-69dbd52101b3-3f60-4cb2-891a-72e1',
];
return $config;
