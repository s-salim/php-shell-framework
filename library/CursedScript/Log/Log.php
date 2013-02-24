<?php

/*
 * This code is part of the CursedScript package.
 *
 * (c) Soufian Salim <soufi@nsal.im>
 *
 * For the full copyright and license information, please view the LICENSE
 * code that was distributed with this source time.
 */

namespace CursedScript\Log;

/**
 * Represents a log code
 *
 * @author Soufian Salim <soufi@nsal.im>
 */
class Log
{
	/**
	 * @var string
	 * @static
	 */
	public static $main_channel;

	/**
	 * @var string
	 * @static
	 */
	public static $info_channel;

	/**
	 * @var string
	 * @static
	 */
	public static $input_channel;

	/**
	 * @var string
	 * @static
	 */
	public static $warning_channel;

	/**
	 * @var string
	 * @static
	 */
	public static $exception_channel;

	/**
	 * @var string
	 * @static
	 */
	public static $error_channel;

	/**
	 * @var string
	 */
	protected $code;

	/**
	 * @var string
	 */
	protected $date;

	/**
	 * @var string
	 */
	protected $time;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var string
	 */
	protected $channel;

	/**
	 * Sets the log channels
	 * 
	 * @param array $channels
	 */
	public static function setChannels(array $channels = array())
	{
		if(isset($config['main']))      Log::$main_channel      = $config['main'];
		if(isset($config['info']))      Log::$info_channel      = $config['info'];
		if(isset($config['input']))     Log::$input_channel     = $config['input'];
		if(isset($config['warning']))   Log::$warning_channel   = $config['warning'];
		if(isset($config['exception'])) Log::$exception_channel = $config['exception'];
		if(isset($config['error']))     Log::$error_channel     = $config['error'];
	}

	/**
	 * Constructor
	 * 
	 * @param int $code
	 * @param array  $data
	 * @param string $channel
	 */
	public function __construct($code, array $data, $channel = null)
	{
		$this->setDate(date('d-m-Y'))
			 ->setTime(date('H:i:s'))
			 ->setCode($code)
			 ->setData($data)
			 ->setChannel($channel);
		
		call_user_func(\CursedScript\Script::getInstance()->getLogger()->getHandle(), $this);
	}

	/**
	 * Returns a json representation of itself
	 * 
	 * @return string
	 */
	public function serialize()
	{
		$json = array();

	    foreach ($this as $property => $value) {
	        $json[$property] = $value;
	    }

	    return json_encode($json);
	}

	/**
	 * Get code
	 *
	 * @return string
	 */
	public function getCode()
	{
	    return $this->code;
	}
	
	/**
	 * Set code
	 *
	 * @param  string $code
	 * @return Log
	 */
	public function setCode($code)
	{
	    $this->code = $code;
	
	    return $this;
	}

	/**
	 * Get date
	 *
	 * @return string
	 */
	public function getDate()
	{
	    return $this->date;
	}
	
	/**
	 * Set date
	 *
	 * @param  string $date
	 * @return Log
	 */
	public function setDate($date)
	{
	    $this->date = $date;
	
	    return $this;
	}

	/**
	 * Get time
	 *
	 * @return string
	 */
	public function getTime()
	{
	    return $this->time;
	}
	
	/**
	 * Set time
	 *
	 * @param  string $time
	 * @return Log
	 */
	public function setTime($time)
	{
	    $this->time = $time;
	
	    return $this;
	}

	/**
	 * Get data
	 *
	 * @return array
	 */
	public function getData()
	{
	    return $this->data;
	}
	
	/**
	 * Set data
	 *
	 * @param  array $data
	 * @return Log
	 */
	public function setData(array $data)
	{
	    $this->data = $data;
	
	    return $this;
	}

	/**
	 * Get channel
	 *
	 * @return string
	 */
	public function getChannel()
	{
	    return $this->channel;
	}
	
	/**
	 * Set channel
	 *
	 * @param  string $channel
	 * @return Log
	 */
	public function setChannel($channel)
	{
	    $this->channel = $channel;
	
	    return $this;
	}
}