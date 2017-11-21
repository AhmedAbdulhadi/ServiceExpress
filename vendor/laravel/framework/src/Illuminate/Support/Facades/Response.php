<?php

namespace Illuminate\Support\Facades;

use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

/**
 * @see \Illuminate\Contracts\Routing\ResponseFactory
 */
class Response extends Facade
{
	public static function json ($data , $getStatusCode , $headers) { }

	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ResponseFactoryContract::class;
    }
}
