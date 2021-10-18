<?php 

return [
	// Auth
	['GET', '/api/auth/url', 'App\Main\Auth\GetInitialUrl'],
	['POST', '/api/auth/token', 'App\Main\Auth\GetUserToken'],
	// Routes and directories
	['GET', '/api/routes', 'App\Main\Routes\GetOverview'],
	['GET', '/api/routes/{id}', 'App\Main\Routes\GetDetails'],
	['PUT', '/api/routes/{id}', 'App\Main\Routes\UpdateDetails']
];