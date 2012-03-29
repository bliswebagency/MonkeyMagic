<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * MonkeyMagic Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Blis
 * @link		
 */

$plugin_info = array(
	'pi_name'		=> 'MonkeyMagic',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Blis',
	'pi_author_url'	=> '',
	'pi_description'=> 'A EE way to interact with MailChimp',
	'pi_usage'		=> Monkeymagic::usage()
);


class Monkeymagic {

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();				
	}
	
	public function subscribe()
	{
		$this->EE =& get_instance();
		
		require_once('libraries/MCAPI.php');
		
		$api = $this->EE->TMPL->fetch_param('api', NULL);
		$list = $this->EE->TMPL->fetch_param('list', NULL);
		$email = $this->EE->TMPL->fetch_param('email', NULL);
		$firstname = $this->EE->TMPL->fetch_param('firstname', NULL);
		$lastname = $this->EE->TMPL->fetch_param('lastname', NULL);
		$doubleoptin = $this->EE->TMPL->fetch_param('doubleoptin', FALSE);
		$updatemember = $this->EE->TMPL->fetch_param('updatemember', TRUE);
				
		$subscriber_data = array(
			'LNAME' => $lastname,
			'FNAME' => $firstname
		);
		
		$mailchimp = new MCAPI($api);
		
		$lists = explode(",",$list);
		$out = "";
		
		foreach ($lists as $l)
		{
		$result = $mailchimp->listSubscribe(
			$list,
			$email,
			$subscriber_data,
			'html', // Send html email
			$doubleoptin, // Send double opt-in email
			$updatemember // Update member if exists
		);
		
		if ($result) $out .= "Subscribed ";
	    }
	    
	    return $out;
	}
	
	public function unsubscribe()
	{
		$this->EE =& get_instance();
		
		require_once('libraries/MCAPI.php');
		
		$api = $this->EE->TMPL->fetch_param('api', NULL);
		$list = $this->EE->TMPL->fetch_param('list', NULL);
		$email = $this->EE->TMPL->fetch_param('email', NULL);		
		$delete = $this->EE->TMPL->fetch_param('delete', FALSE);
		$goodbye = $this->EE->TMPL->fetch_param('goodbye', FALSE);
		$notify = $this->EE->TMPL->fetch_param('notify', FALSE);
								
		$mailchimp = new MCAPI($api);
		
		$lists = explode(",",$list);
		$out = "";
		
		foreach ($lists as $l)
		{
			$result = $mailchimp->listUnsubscribe(
				$l,
				$email,
				$delete, // Delete
				$goodbye, // Goodbye
				$notify //NOTIFY
			);

			if ($result) $out .= "Unsubscribed from $l ";	
		}
		
		return $out;
		
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>

 Since you did not provide instructions on the form, make sure to put plugin documentation here.
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.monkeymagic.php */
/* Location: /system/expressionengine/third_party/monkeymagic/pi.monkeymagic.php */