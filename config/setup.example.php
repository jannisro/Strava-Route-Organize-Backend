<?php

return (object)[
	'cipher' => 'long_and_secret_key_for_encryption', // Random string for encrpytion
	'cipher_algo' => 'aes-256-cbc-hmac-sha256', // Algorithm to encrypt credentials
	'strava_app_id' => 12345, // Can be found in the Strava API dashboard
	'strava_app_secret' => 'secret', // Can be found in the Strava API dashboard
];
