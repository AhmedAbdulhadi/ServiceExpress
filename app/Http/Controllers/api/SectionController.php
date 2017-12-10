<?php

	namespace App\Http\Controllers\api;

	use App\Http\Controllers\SectionServices;
	use App\Http\Controllers\ServicesC;
	use Illuminate\Support\Facades\Response;
	use Novent\Transformers\AddressTransfomer;
	use Illuminate\Http\Request;
	use Novent\Transformers\sectionTransform;

	class SectionController extends SectionServices
	{

		/**
		 * @var  Novent\Transformers\userTransfomer
		 */
		protected $userTrans;

		public function __construct (sectionTransform $userTrans)
		{
			$this->userTrans = $userTrans;

			$this->content = array ();
			$this->middleware ( 'auth:api' );
//			$this->middleware ('auth:api')->except ('login', 'logout');


		}

		public function index ()
		{
			return $this->getAllSection ();
		}

		public function store (Request $request)
		{
			return $this->create_Section ( $request );
		}

		public function update (Request $request , $id)
		{
			return $this->update_section ( $request , $id );

		}

		public function destroy ($id)
		{
			return $this->delete_Section ( $id );
		}

		public function get_section_id (Request $request , $id)
		{
			return $this->get_sectionid ( $request , $id );
		}

		public function show ($id , Request $request)
		{
			return $this->get_one_user ( $id , $request );


		}


		public function section_with_service (Request $request)
		{
			return $this->section_with_services ( $request );
		}
	}
