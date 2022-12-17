<?php
defined('BASEPATH') OR exit('No direct script access allowed');

###
##  BUILD SESSION
###
$sessDir = session_save_path();
$sessDir = "{$sessDir}/sessionPath";
\is_dir($sessDir) ?: \mkdir($sessDir);

###
##  APPLICATION TOKENS
###
$config['app_token'] = "";


###
##  APPLICATION CONFIGURATION
###
$config['base_url']	    = "http://".$_SERVER['HTTP_HOST'].\str_replace(\str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']), '', str_replace("\\", "/", str_replace(SELF, '', FCPATH)) );
$config['user_images']  = 'uploads/users/images/';

###
##  SESSION CONFIGURATION
###
$config['sess_driver']              = 'files';
$config['sess_cookie_name']         = 'ci_session';
$config['sess_expiration']          = 31536000; // 1 year
$config['sess_save_path']           = $sessDir;
$config['sess_match_ip']            = FALSE;
$config['sess_time_to_update']      = 300;
$config['sess_regenerate_destroy']  = FALSE;

###
## MAIL CONFIGURATION
###
$config['protocol']       = 'smtp'; # smtp | mail | sendmail
$config['smtp_host']      = 'smtp.gmail.com';
$config['smtp_port']      = '587'; # 465 | 587
$config['smtp_user']      = 'manageexpenses.in@gmail.com';
$config['smtp_pass']      = 'uiohtxonytwwxmrw'; # uiohtxonytwwxmrw | ManageExpenses@2021
$config['smtp_crypto']    = 'tls'; # ssl | tls
$config['mailtype']       = 'html'; # text or html
$config['smtp_timeout']   = '7';
$config['charset']        = 'utf-8'; # utf-8 | iso-8859-1
$config['wordwrap']       = TRUE;
$config['crlf']           = '\r\n';
$config['newline']        = '\r\n';

###
##  LOGGING CONFIGURATION
###
$config['log_threshold']        = 4; # 0-disabled | 1-error | 2-debug | 3-info | 4-all | usage-[1,2]
$config['log_path']             = FCPATH.'/logs/';
$config['log_file_extension']   = '.txt';
$config['log_file_permissions'] = 0777;
$config['log_date_format']      = 'Y-m-d H:i:s';


# Config
$config['index_page'] = '';
$config['uri_protocol']	= 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']	= 'english';

$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = "vendor/autoload.php";
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
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