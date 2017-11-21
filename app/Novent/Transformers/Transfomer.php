<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 9/16/2017
	 * Time: 3:13 PM
	 */
	namespace Novent\Transformers;

	abstract class Transfomer
	{

/**
 * Transfomer a Collection of lessons
 * @param $items
 * @return array
 *
 * */

		public function transformCollection (array $items)
		{
			return array_map ([$this, 'transform'], $items);
		}


		public abstract function transform($item);
	}