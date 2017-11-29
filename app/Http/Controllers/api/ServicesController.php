<?php

	namespace App\Http\Controllers\api;

	use App\address;
	use App\Http\Controllers\AddressServices;
	use App\Http\Controllers\Controller;
	use App\Http\Controllers\ServicesC;
	use Illuminate\Support\Facades\Response;
	use Novent\Transformers\AddressTransfomer;
	use Illuminate\Http\Request;

	class ServicesController extends ServicesC
	{


		public function index ()
		{
			return $this->getAllServices ();
		}

		public function store (Request $request)
		{
			return $this->create_Service ( $request );
		}

		public function update (Request $request , $id)
		{
			return $this->update_services ( $request , $id );

		}

		public function destroy ($id)
		{
			return $this->delete_services ( $id );
		}

//
		public function show ($id)
		{
			return $this->get_one_services ( $id );
		}

		public function show_by_section_id (Request $request)
		{
			return $this->get_one_services_by_section_id ( $request );
		}

		public function services_suppiler (Request $request)
		{
			return $this->services_suppilers ( $request );
		}


		public function services_supplier_id (Request $request)
		{
			return $this->services_supplier_id_s ( $request );

		}

		public function services_section_supplier_id_s (Request $request)
		{
			return $this->services_section_supplier_id ( $request );
		}


		public function assigned_services (Request $request)
		{

			return $this->assign_services ( $request );
		}

		public function unAssigned_services (Request $request)
		{

			return $this->unAssign_services ( $request );
		}
//
//
//		public function get_date (Request $request)
//		{
//			return $this->get_date_Query($request);
//		}
	}
