<?php
/**
 * Benchmark class file.
 *
 * @author Z. Alem <info@alemcode.com>
 * @link http://alemcode.com
 * @copyright Copyright 2012, Z.Alem
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The Benchmark class performs basic benchmarking calculations related to timing and memory consumption.
 * @author Z. Alem <info@alemcode.com>
 * @package minimvc.base
 */
class Benchmark
{

	/**
	 * An array containing times of named markers
	 * @var array
	 */
	public $mark = array();

	/**
	 * markTime - Creates a named marker with its time in microseconds
	 *
	 * @param string $name 	The name of the marker
	 * @return float 	The marked time
	 */
	public function markTime( $name )
	{
		return $this->mark[$name] = microtime(true);
	}

	/**
	 * timeBetween - Calculates the time elapsed between to named time marks
	 *
	 * @param string $name_1 	The name of the marker one
	 * @param string $name_2 	The name of the marker two
	 * @return float 		The marked time
	 */
	public function timeBetween( $name_1, $name_2 )
	{
		return $this->mark[$name_1] - $this->mark[$name_2];
	}

	/**
	 * memoryUsage - Returns memory usage in kb
	 *
	 * @return string 	Memory usage
	 */
	public function memoryUsage()
	{
		return ( memory_get_usage() / 1000 ) . ' kb';
	}

	/**
	 * peakMemoryUsage - Returns peak memory usage in kb
	 *
	 * @return string 	Peak memory usage
	 */
	public function peakMemoryUsage()
	{
		return ( memory_get_peak_usage() / 1000 ) . ' kb';
	}

}

?>
