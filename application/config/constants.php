<?php
defined('BASEPATH') OR exit('No direct script access allowed');
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
/**** USER DEFINED CONSTANTS **********/
define('ROLE_ADMIN',                            '1');
define('ROLE_MANAGER',                         	'2');
define('ROLE_TELE_CALLER_EMPLOYEE',             '5');//tele caller
define('ROLE_FIELD_EXECUTIVE_EMPLOYEE',         '6');//tele caller
define('ROLE_TECHNICAL_EMPLOYEE',         '7');//technial employee
define('ROLE_AUDITOR',         '8');//auditor
define('ROLE_PLANNING_EMPLOYEE',         '12');//planning employee
define('SEGMENT',								2);
define('CRM_NAME',								'ACT IASISO');
/************************** EMAIL CONSTANTS *****************************/
define('EMAIL_DOMAIN',                          'iasiso.com');		// e.g. email@example.com
define('EMAIL_HOST',                            'iasiso.com');		// e.g. email@example.com
define('EMAIL_FROM',                            'info@act.iasiso.com');		// e.g. email@example.com
//define('EMAIL_BCC',                            	'certificationmanager@iasiso.com');		// e.g. email@example.com
define('EMAIL_BCC',                            	'haiabhar@gmail.com');		// e.g. email@example.com
define('FROM_NAME',                             'ACT IASISO');	// Your system name
define('EMAIL_PASS',                            '5N,CZL8?Uoa!');	// Your email password
define('PROTOCOL',                             	'smtp');				// mail, sendmail, smtp
define('SMTP_HOST',                             'www.act.iasiso.com');		// your smtp host e.g. smtp.gmail.com
define('SMTP_PORT',                             '587');					// your smtp port e.g. 25, 587 ,465
define('SMTP_USER',                             'info@act.iasiso.com');		// your smtp user
define('SMTP_PASS',                             '5N,CZL8?Uoa!');	// your smtp password
define('MAIL_PATH',                             '/usr/sbin/sendmail');