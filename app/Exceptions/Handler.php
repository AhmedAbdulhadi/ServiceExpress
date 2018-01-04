<?php

	namespace App\Exceptions;

	use App\Http\Controllers\ResponseCode;
	use App\Http\Controllers\ResponseDisplay;
	use App\Http\Controllers\ResponseMassage;
	use App\Http\Controllers\ResponseStatus;
	use App\Http\Controllers\UserServices;
	use App\UserModel;
	use Exception;
	use Illuminate\Auth\AuthenticationException;
	use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
	use Illuminate\Support\Facades\Response;
//	use Illuminate\Support\Facades\Response;

	use Illuminate\Http\Request;
	use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	class Handler extends ExceptionHandler
	{
		/**
		 * A list of the exception types that should not be reported.
		 *
		 * @var array
		 */
		protected $dontReport = [
			\Illuminate\Auth\AuthenticationException::class ,
			\Illuminate\Auth\Access\AuthorizationException::class ,
			\Symfony\Component\HttpKernel\Exception\HttpException::class ,
			\Illuminate\Database\Eloquent\ModelNotFoundException::class ,
			\Illuminate\Session\TokenMismatchException::class ,
			\Illuminate\Validation\ValidationException::class ,
		];

		/**
		 * Report or log an exception.
		 *
		 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
		 *
		 * @param  \Exception $exception
		 * @return void
		 */
		public function report (Exception $exception)
		{
			parent::report ( $exception );
		}

		/**
		 * Render an exception into an HTTP response.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @param  \Exception $exception
		 * @return \Illuminate\Http\Response
		 */
		public function render ($request , Exception $exception)
		{
			if ( $exception instanceof MethodNotAllowedHttpException ) {
				if ( $request->expectsJson () ) {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Method_not_allowed_Massages_en , ResponseStatus::$fail
						, ResponseCode::$HTTP_NOT_ALLOWED );

					return $objResponse->returnWithOutData ();

				}

				$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_invalid_Massages_en , ResponseStatus::$fail
					, ResponseCode::$HTTP_FORBIDDEN );

				return $objResponse->returnWithOutData ();
			}


			if ( $exception instanceof NotFoundHttpException ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_invalid_Massages_en , ResponseStatus::$fail
					, ResponseCode::$HTTP_FORBIDDEN );

				return $objResponse->returnWithOutData ();
			}


			if ( $exception instanceof AuthenticationException ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Unauthorized_Massages_en , ResponseStatus::$fail
					, ResponseCode::$HTTP_UNAUTHORIZED );

				return $objResponse->returnWithOutData ();
			}


//	    return parent::render($request, $exception);
			return parent::render ( $request , $exception );


		}

		/**
		 * Convert an authentication exception into an unauthenticated response.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @param  \Illuminate\Auth\AuthenticationException $exception
		 * @return \Illuminate\Http\Response
		 */
		protected function unauthenticated ($request , AuthenticationException $exception)
		{
			if ( $request->expectsJson () ) {

				$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Unauthorized_Massages_en , ResponseStatus::$fail
					, ResponseCode::$HTTP_UNAUTHORIZED );

				return $objResponse->returnWithOutData ();
			}

			$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Unauthorized_Massages_en , ResponseStatus::$fail
				, ResponseCode::$HTTP_UNAUTHORIZED );

			return $objResponse->returnWithOutData ();

		}


	}
