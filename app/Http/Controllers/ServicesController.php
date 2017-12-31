<?php

	namespace App\Http\Controllers\api;

	use App\adressModel;
	use App\Http\Controllers\AddressServices;
	use App\Http\Controllers\Controller;
	use App\Http\Controllers\ServicesSer;
	use App\SectionModel;
	use Illuminate\Support\Facades\Response;
	use Unit\Transformers\AddressTransfomer;
	use Illuminate\Http\Request;

	class ServicesController extends Controller
	{


		public function index ()
		{
			return ServicesSer::getAllServices ();
		}

		public function store (Request $request)
		{
			return ServicesSer::createServices ( $request );
		}

		public function update (Request $request , $id)
		{
			return ServicesSer::updateServices ( $request , $id );

		}

		public function destroy ($id)
		{
			return ServicesSer::deleteServices ( $id );
		}

//
		public function show ($id)
		{
			return ServicesSer::getServicesById ( $id );
		}

		public function getOneServicesBySectionId (Request $request)
		{
			return ServicesSer::getOneServicesBySectionId ( $request );
		}

		public function getServicesSuppliers (Request $request)
		{
			return ServicesSer::getServicesSuppliers ( $request );
		}


		public function getServicesWithSupplierId (Request $request)
		{
			return ServicesSer::getServicesWithSupplierId ( $request );

		}

		public function getServicesSectionIdSupplierId (Request $request)
		{
			return ServicesSer::getServicesSectionIdSupplierId ( $request );
		}


		public function assignServices (Request $request)
		{

			return ServicesSer::assignServices ( $request );
		}

		public function unAssignServices (Request $request)
		{

			return ServicesSer::unAssignServices ( $request );
		}
//
//
//		public function get_date (Request $request)
//		{
//			return $this->get_date_Query($request);
//		}
	}
