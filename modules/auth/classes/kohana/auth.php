<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * User authorization library. Handles user login and logout, as well as secure
 * password hashing.
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2010 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Kohana_Auth {

	// Auth instances
	protected static $_instance;

	/**
	 * Singleton pattern
	 *
	 * @return Auth
	 */
	public static function instance()
	{
		if ( ! isset(Auth::$_instance))
		{
			// Load the configuration for this type
			$config = Kohana::config('auth');

			if ( ! $type = $config->get('driver'))
			{
				$type = 'file';
			}

			// Set the session class name
			$class = 'Auth_'.ucfirst($type);

			// Create a new session instance
			Auth::$_instance = new $class($config);
		}

		return Auth::$_instance;
	}

	protected $_session;

	protected $_config;

	/**
	 * Loads Session and configuration options.
	 *
	 * @return  void
	 */
	public function __construct($config = array())
	{
		// Save the config in the object
		$this->_config = $config;

		$this->_session = Session::instance();
	}

	abstract protected function _login($email, $password, $remember);

	abstract public function password($email);

	abstract public function check_password($password);

	/**
	 * Gets the currently logged in user from the session.
	 * Returns NULL if no user is currently logged in.
	 *
	 * @return  mixed
	 */
	public function get_user($default = NULL)
	{
		return $this->_session->get($this->_config['session_key'], $default);
	}

	/**
	 * Attempt to log in a user by using an ORM object and plain-text password.
	 *
	 * @param   string   email to log in
	 * @param   string   password to check against
	 * @param   boolean  enable autologin
	 * @return  boolean
	 */
	public function login($email, $password, $remember = FALSE)
	{
		if (empty($password))
			return FALSE;

		if (is_string($password))
		{
			// Create a hashed password
			$password = $this->hash($password);
		}

		return $this->_login($email, $password, $remember);
	}

	/**
	 * Log out a user by removing the related session variables.
	 *
	 * @param   boolean  completely destroy the session
	 * @param   boolean  remove all tokens for user
	 * @return  boolean
	 */
	public function logout($destroy = FALSE, $logout_all = FALSE)
	{
		if ($destroy === TRUE)
		{
			// Destroy the session completely
			$this->_session->destroy();
		}
		else
		{
			// Remove the user from the session
			$this->_session->delete($this->_config['session_key']);

			// Regenerate session_id
			$this->_session->regenerate();
		}

		// Double check
		return ! $this->logged_in();
	}

	/**
	 * Check if there is an active session. Optionally allows checking for a
	 * specific role.
	 *
	 * @param   string   role name
	 * @return  mixed
	 */
	public function logged_in($role = NULL)
	{
		return ($this->get_user() !== NULL);
	}

	/**
	 * Creates a hashed hmac password from a plaintext password. This
	 * method is deprecated, [Auth::hash] should be used instead.
	 *
	 * @deprecated
	 * @param   string  plaintext password
	 */
	public function hash_password($password)
	{
		return $this->hash($password);
	}

	/**
	 * Perform a hmac hash, using the configured method.
	 *
	 * @param   string  string to hash
	 * @return  string
	 */
	public function hash($str)
	{
		if ( ! $this->_config['hash_key'])
			throw new Kohana_Exception('A valid hash key must be set in your auth config.');

		return hash_hmac($this->_config['hash_method'], $str, $this->_config['hash_key']);
	}

	protected function complete_login($user)
	{
		// Regenerate session_id
		$this->_session->regenerate();

		// Store email in session
		$this->_session->set($this->_config['session_key'], $user);

		return TRUE;
	}

} // End Auth
