<?php

/**
 * A class that reproduces bash's pushd and popd functionality in PHP
 * @author ben.tyger@tygerclan.net
 * @license Apache 2.0
 * @version 0.1
 */
class PushPop {
	/**
	 * Internal directory stack 
	 * @var array
	 */
	protected $stack = array();

	/**
	 * Changes to $newDir and adds previous current directory to the stack
	 * Returns FALSE when an error exists
	 * @param string $newDir Change directory to $newDir
	 * @param boolean $beginning Add the directory to the start of the stack 
	 * instead of the end.
	 * @return string Resulting directory location
	 */
	public function pushd($newDir, $beginning = FALSE) {
		$currentDir=getcwd();
		if (chdir($newDir)) {
			if ($beginning) array_unshift($this->stack, $currentDir);
			else $this->stack[] = $currentDir;
			return $newDir;
		} else {
			trigger_error('Could not chdir() to directory '.$newDir,E_USER_WARNING);
			return FALSE;
		}
	}

	/**
	 * Returns current working directory to a previous directory in the 
	 * directory stack.
	 * Returns FALSE when an error exists 
	 * @param int $layers Iterate over $layers layers in the directory stack.  DEFAULT: 1
	 * @param boolean $beginning Pull from the begininng of the directory stack 
	 * instead of the end. DEFAULT: FALSE
	 * @return string Resulting directory location
	 */
	public function popd($layers = 1, $beginning = FALSE) {
		$testStack = $this->stack;
		for ($h=0; $h<$layers;$h++) {
			if ($beginning) $testDir = array_shift($testStack);
			else $testDir = array_pop($testStack);
			if ($testDir === NULL) {
				trigger_error('Requested layers exceeds number of directory stack layers.',E_USER_NOTICE);
				return FALSE;
			}
		}		
		if (@chdir($testDir)) {
			$this->stack = $testStack;
		} else {
			trigger_error('Could not chdir() to '.$testDir,E_USER_WARNING);
			return FALSE;
		}
		return $testDir;
	}
	
	/**
	 * Returns the directory stack as an array
	 * @return array Array of directory locations
	 */
	public function getStack() {
		return $this->stack;
	}
}

?>
