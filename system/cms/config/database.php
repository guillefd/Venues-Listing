<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Environments'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] true/false - Whether to use a persistent connection
|	['db_debug'] true/false - Whether database errors should be displayed.
|	['cache_on'] true/false - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
*/

// Development
$db[PYRO_DEVELOPMENT] = array(
	'hostname'		=> 	'localhost',
	'username'		=> 	'root',
	'password'		=> 	'12345',
	'database'		=> 	'amrooms',
	'dbdriver' 		=> 	'mysql',
	'dbprefix' 		=>	'',
	'active_r' 		=>	true,
	'pconnect' 		=>	false,
	'db_debug' 		=>	true,
	'cache_on' 		=>	false,
	'char_set' 		=>	'utf8',
	'dbcollat' 		=>	'utf8_unicode_ci',
	'port' 	 		=>	3306,

	// 'Tough love': Forces strict mode to test your app for best compatibility
	'stricton' 		=> true,
);

// Staging
/*
$db[PYRO_STAGING] = array(
	'hostname'		=> 	'',
	'username'		=> 	'',
	'password'		=> 	'',
	'database'		=> 	'pyrocms',
	'dbdriver' 		=> 	'mysql',
	'pconnect' 		=>	false,
	'db_debug' 		=>	false,
	'cache_on' 		=>	false,
	'char_set' 		=>	'utf8',
	'dbcollat' 		=>	'utf8_unicode_ci',
	'port' 	 		=>	3306,
);
*/

// Production
$db[PYRO_PRODUCTION] = array(
	'hostname'		=> 	'localhost',
	'username'		=> 	'amrooms_amrweb',
	'password'		=> 	'',
	'database'		=> 	'amrooms_amrpyro22',
	'dbdriver' 		=> 	'mysql',
	'pconnect' 		=>	false,
	'db_debug' 		=>	false,
	'cache_on' 		=>	false,
	'char_set' 		=>	'utf8',
	'dbcollat' 		=>	'utf8_unicode_ci',
	'port' 	 		=>	3306,
);


// Check the configuration group in use exists
if ( ! array_key_exists(ENVIRONMENT, $db))
{
	show_error(sprintf(lang('error_invalid_db_group'), ENVIRONMENT));
}

// Assign the group to be used
$active_group = ENVIRONMENT;
$query_builder = true;

/* End of file database.php */
