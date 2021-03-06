<?php

/*
 * This file is part of the CursedScript package.
 *
 * (c) Soufian Salim <soufi@nsal.im>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CursedScript\GUI;

use CursedScript\GUI\Theme\Visual;

/**
 * The full screen container
 *
 * @author Soufian Salim <soufi@nsal.im>
 */
class Screen extends Window
{
	/**
	 * @var array
	 */
	protected $windows = array();

	/**
	 * Constructs the screen
	 */
	public function __construct()
	{
		parent::__construct(0, 0, 0, 0);

		$this->bottom();
	}

	/**
	 * Clears the screen and its windows
	 * 
	 * @return Screen
	 */
	public function clear()
	{
		ncurses_clear();

		foreach ($this->windows as $window){
			$window->clear();
		}

		return $this;
	}

	/**
	 * Get windows
	 *
	 * @return array
	 */
	public function getWindows()
	{
	    return $this->windows;
	}
	
	/**
	 * Set windows
	 *
	 * @param  array $windows
	 * @return Window
	 */
	public function setWindows(array $windows)
	{
	    $this->windows = $windows;
	
	    return $this;
	}
	
	/**
	 * Add window
	 *
	 * @param  Window $window
	 * @return Window
	 */
	public function addWindow(Window $window)
	{
	    $this->windows[] = $window;
	
	    return $this;
	}
	
	/**
	 * Remove window
	 *
	 * @param  Window $window
	 * @return Window
	 */
	public function removeWindow(Window $window)
	{
	    $this->windows = array_diff($this->windows, array($window));
	
	    return $this;
	}
}