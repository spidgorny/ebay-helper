<?php

use DTS\eBaySDK\Credentials\CredentialsProvider;

$config['sandbox']['credentials'] = [
	CredentialsProvider::ENV_APP_ID => 'SlawaPid-SearchHe-SBX-7dbc19de5-45bcbcc9',
	CredentialsProvider::ENV_DEV_ID => '63e7a23e-a574-4452-a204-77d938f845b2',
	CredentialsProvider::ENV_CERT_ID => 'SBX-7a9b454fe3b1-6eda-4030-bcab-841f',
];
$config['production']['credentials'] = [
	CredentialsProvider::ENV_APP_ID => 'SlawaPid-SearchHe-PRD-d69dbd521-eb745828',
	CredentialsProvider::ENV_DEV_ID => '63e7a23e-a574-4452-a204-77d938f845b2',
	CredentialsProvider::ENV_CERT_ID => 'PRD-7a9b4557627b-1b98-41bf-95fe-4500',
];
return $config;
