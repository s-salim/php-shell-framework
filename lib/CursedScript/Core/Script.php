<?php

/*
 * This file is part of the CursedScript package.
 *
 * (c) Soufian Salim <soufi@nsal.im>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CursedScript;

use \CursedScript\GUI\Screen;

/**
 * The Script class provides advanced console functionalities to classes who extend it
 *
 * @author Soufian Salim <soufi@nsal.im>
 */
abstract class Script implements GUI\GUI
{
	/**
	 * @var Script
	 * @static
	 */
	public static $instance;

	/**
	 * @var string
	 */
	protected $ini;

	/**
	 * @var boolean
	 */
	protected $initialized;

	/**
	 * @var Shell\Terminal
	 */
	protected $terminal;

	/**
	 * @var Screen
	 */
	protected $screen;

	/**
	 * @var Log\Handler
	 */
	protected $log_handler;

	/**
	 * @var Error\Handler
	 */
	protected $error_handler;

	/**
	 * @var Exception\Handler
	 */
	protected $exception_handler;

	/**
	 * Get instance
	 *
	 * @return Script
	 */
	public static function getInstance()
	{
	    return Script::$instance;
	}

	/**
	 * Constructor
	 * Sets exception and error handlers, then starts, runs and stops the script
	 */
	final public function __construct()
	{
		self::$instance = $this;

		$this->terminal          = new Shell\Terminal();
		$this->log_handler       = new Log\Handler();
		$this->error_handler     = new Error\Handler();
		$this->exception_handler = new Exception\Handler();

		$this->init();
		$this->start();
		$this->run();
		$this->stop();
	}

	/**
	 * Destructor
	 * Restores exception and error handlers
	 */
	final public function __destruct() 
	{
		restore_exception_handler();
		restore_error_handler();

		if ($this->initialized){
			ncurses_echo();
			ncurses_end();
		}
	}

	/**
	 * Starts the script
	 */
	final public function start()
	{
		// Handlers
		set_error_handler($this->error_handler->getHandle());
		set_exception_handler($this->exception_handler->getHandle());

		// Config
		$config = $this->_configure();

		// Log
		new Log\Log('SCRIPT_STARTS', array($config), Log\Log::$info_channel);

		// Ncurses initialization
		ncurses_init();

		$this->initialized = true;

		// Apply terminal settings
		$this->terminal->apply();
	}

	/**
	 * Stops the script
	 */
	final public function stop()
	{
		new Log\Log('SCRIPT_STOPPED', array(), Log\Log::$info_channel);

		$this->__destruct();
	}

	/**
	 * Refreshes the screen and its windows
	 * 
	 * @return Script
	 */
	final public function refresh()
	{
		new Log\Log('REFRESH', array(), Log\Log::$info_channel);

		ncurses_update_panels();
		ncurses_doupdate();

		return $this;
	}

	/**
	 * Select the displayed screen
	 * 
	 * @return Script
	 */
	final public function select(Screen $screen)
	{
		new Log\Log('SCREEN_SELECTION', array($screen), Log\Log::$info_channel);

		if (isset($this->screen)) $this->screen->hide();

		$screen->show();

		return $this;
	}

	/**
	 * Script configuration
	 */
	final private function _configure()
	{
		// INI settings
		if (!isset($this->ini)) $this->ini = dirname(__DIR__) . '/settings.ini';

		if (file_exists($this->ini)){
			$config = parse_ini_file($this->ini, true);
		} else {
			return;
		}

		// Log channels config
		if(isset($config['channels'])) Log\Log::setChannels($config['channels']);

		// Logger config
		if(isset($config['logger']['dir'])) $this->log_handler->setDir($config['logger']['dir']);

		// Terminal config
		if(isset($config['terminal']['echo']))   $this->terminal->setEcho($config['terminal']['echo']);
		if(isset($config['terminal']['raw']))    $this->terminal->setRaw($config['terminal']['raw']);
		if(isset($config['terminal']['cursor'])) $this->terminal->setCursor($config['terminal']['cursor']);
		if(isset($config['terminal']['keypad'])) $this->terminal->setKeypad($config['terminal']['keypad']);
		if(isset($config['terminal']['color']))  $this->terminal->setColor($config['terminal']['color']);

		return $config;
	}

	/**
	 * Custom script initialization
	 */
	abstract public function init();

	/**
	 * Custom script execution
	 */
	abstract public function run();

	/**
	 * Get ini
	 *
	 * @return string
	 */
	final public function getIni()
	{
	    return $this->ini;
	}
	
	/**
	 * Set ini
	 *
	 * @param  string $ini
	 * @return Script
	 */
	final public function setIni($ini)
	{
	    $this->ini = $ini;
	
	    return $this;
	}

	/**
	 * Get initialized
	 *
	 * @return boolean
	 */
	public function getInitialized()
	{
	    return $this->initialized;
	}
	
	/**
	 * Set initialized
	 *
	 * @param  boolean $initialized
	 * @return Script
	 */
	public function setInitialized($initialized)
	{
	    $this->initialized = $initialized;
	
	    return $this;
	}

	/**
	 * Get terminal
	 *
	 * @return Shell\Terminal
	 */
	public function getTerminal()
	{
	    return $this->terminal;
	}
	
	/**
	 * Set terminal
	 *
	 * @param  Shell\Terminal $terminal
	 * @return Shell\Terminal
	 */
	public function setTerminal($terminal)
	{
	    $this->terminal = $terminal;
	
	    return $this;
	}

	/**
	 * Get screen
	 *
	 * @return Screen
	 */
	public function getScreen()
	{
	    return $this->screen;
	}
	
	/**
	 * Set screen
	 *
	 * @param  Screen $screen
	 * @return Script
	 */
	public function setScreen($screen)
	{
	    $this->screen = $screen;
	
	    return $this;
	}

	/**
	 * Get log_handler
	 *
	 * @return Log\Handler
	 */
	final public function getLogHandler()
	{
	    return $this->log_handler;
	}
	
	/**
	 * Set log_handler
	 *
	 * @param  Log\Handler $log_handler
	 * @return Script
	 */
	final public function setLogHandler(Log\Handler $log_handler)
	{
	    $this->log_handler = $log_handler;
	
	    return $this;
	}

	/**
	 * Get error_handler
	 *
	 * @return Error\Handler
	 */
	public function getErrorHandler()
	{
	    return $this->error_handler;
	}
	
	/**
	 * Set error_handler
	 *
	 * @param  Error\Handler $error_handler
	 * @return Script
	 */
	public function setErrorHandler($error_handler)
	{
	    $this->error_handler = $error_handler;
	
	    return $this;
	}

	/**
	 * Get exception_handler
	 *
	 * @return Exception\Handler
	 */
	public function getExceptionHandler()
	{
	    return $this->exception_handler;
	}
	
	/**
	 * Set exception_handler
	 *
	 * @param  Exception\Handler $exception_handler
	 * @return Script
	 */
	public function setExceptionHandler($exception_handler)
	{
	    $this->exception_handler = $exception_handler;
	
	    return $this;
	}
}