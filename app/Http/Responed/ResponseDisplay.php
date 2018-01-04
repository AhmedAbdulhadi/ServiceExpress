<?php
	namespace App\Http\Controllers;
//	use Illuminate\Http\Response;

	use App\UserModel;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Response;

	class ResponseDisplay
	{

		private $statusCode ;
		private $status;
		private  $message;

		 public function __construct($msg , $httpStatus, $statusCode)
		{

			$this->setStatusCode ($statusCode);
			$this->setStatus ($httpStatus);
			$this->setMessage ($msg) ;
		}



		public function returnWithOutData()
		{
			$aResponse= $this->respond ([
				'massage'=>$this->getMessage (),
				'status'=>$this->getStatus (),
				'code'=>$this->getStatusCode ()
				]);
			return $aResponse;
		}

		public function returnWithData($data)
		{
			return $this->respond ([
				'massage'=>$this->getMessage (),
				'status'=>$this->getStatus (),
				'code'=>$this->getStatusCode (),
				'size'=>count ($data),
				'data'=>$data,
			]);
		}
		public function returnWithDataServicesIsAdded($data,$data2)
		{
			$arr= array_merge ($data,$data2);
			return $this->respond ([
				'massage'=>$this->getMessage (),
				'status'=>$this->getStatus (),
				'code'=>$this->getStatusCode (),
				'size'=>count ($arr),
				'data'=>$arr,
			]);
		}

		public function  respond ($data , $headers = [])
		{
//			return \Response::json ( $data,'','' );
			return response ()->json ( $data , $this->getStatusCode () ,$headers );

		}




		public function setMessage($msg){

			$this->message = $msg;

		}
		public function getMessage(){

			return $this->message;

		}
		public function getStatusCode ()
		{
			return $this->statusCode;
		}

		public function setStatusCode ($statusCode)
		{
			$this->statusCode =(string) $statusCode;

			return $this;
		}



		public function setStatus($status){


			$this->status = (string)$status;
		}
		public function getStatus ()
		{
			return $this->status;
		}








	}

