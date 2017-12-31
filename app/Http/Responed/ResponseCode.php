<?php

	namespace App\Http\Controllers;
	class ResponseCode
	{
		//HTML code 200
		public static $HTTP_OK = 200;
		public static $HTTP_CREATED = 201;

		//HTML code 400
		public static $HTTP_BAD_REQUEST = 400;
		public static $HTTP_UNAUTHORIZED = 401;
		public static $HTTP_PAYMENT_REQUIRED = 402;
		public static $HTTP_FORBIDDEN = 403;
		public static $HTTP_NOT_FOUND = 404;
		public static $HTTP_NOT_ALLOWED = 405;
		public static $HTTP_INTERNAL_SERVER_ERROR = 500;
		public static $HTTP_NOT_IMPLEMENTED = 501;

		//HTML code 500
		public static $HTTP_BAD_GATEWAY = 502;
		public static $HTTP_SERVICE_UNAVAILABLE = 503;
		public static $HTTP_GATEWAY_TIMEOUT = 504;
	}