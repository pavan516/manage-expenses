<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Config
$config['index_page'] = '';
$config['uri_protocol']	= 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']	= 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = "vendor/autoload.php";
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['log_threshold'] = 0;
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;
$config['encryption_key'] = '02527-269-2503946-70386-34730519';
$config['database_key']="abc123@#";
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE;
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 14400;
$config['csrf_regenerate'] = FALSE;
$config['csrf_exclude_uris'] = array();
$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';

###
##  APPLICATION RELATED CONFIG
###
$config['base_url']	    = "https://".$_SERVER['HTTP_HOST'].\str_replace(\str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']), '', str_replace("\\", "/", str_replace(SELF, '', FCPATH)) );
$config['user_images']  = 'uploads/users/images/';

###
##  FIREBASE RELATED CONFIG
###
$config['FIREBASE_API_KEY'] = 'AAAA49VzWC8:APA91bFRv5w8iREzTeWYjDkyzZCGzr8O4lOG-HwoHzr4ej8JXPKXveT3IRw1ZzoW_0h90lglezsV5MFTeQ_L8d-4H77ZHdWVs93T3PXXu1jGGhNe-ojWuyNyN2EAe0QuOkDVItRZipgj';

###
##  SESSION RELATED STUFF
###
##  BUILD SESSION RELATED PATH
$sessDir = session_save_path();
$sessDir = "{$sessDir}/sessionPath";
\is_dir($sessDir) ?: \mkdir($sessDir);
##  SESSION RELATED CONFIG
$config['sess_driver']              = 'files';
$config['sess_cookie_name']         = 'ci_session';
$config['sess_expiration']          = 31536000; // 1 year
$config['sess_save_path']           = $sessDir;
$config['sess_match_ip']            = FALSE;
$config['sess_time_to_update']      = 300;
$config['sess_regenerate_destroy']  = FALSE;