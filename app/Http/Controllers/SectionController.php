<?php

	namespace App\Http\Controllers\api;

	use App\Http\Controllers\Controller;
	use App\Http\Controllers\SectionServices;
	use App\Http\Controllers\ServicesSer;
	use App\SectionModel;
	use Illuminate\Support\Facades\Response;
	use Unit\Transformers\AddressTransfomer;
	use Illuminate\Http\Request;
	use Unit\Transformers\sectionTransform;

	class SectionController extends Controller
	{


		public function __construct ()
		{


			$this->content = array ();
			$this->middleware ( 'auth:api' );
//			$this->middleware ('auth:api')->except ('login', 'logout');


		}

		public function index ()
		{
			return SectionServices::getAllSection ();
		}

		public function store (Request $request)
		{
			return SectionServices::createSection ( $request );
		}

		public function update (Request $request , $id)
		{
			return SectionServices::updateSection ( $request , $id );

		}

		public function destroy ($id)
		{
			return SectionServices::deleteSection ( $id );
		}

		public function get_section_id (Request $request , $id)
		{
//			dd('section_id');
			return SectionServices::getSectionWithServices ( $request , $id );

		}

		public function show ($id)
		{

//			dd('id');
			return SectionServices::getOneSectionById ( $id );


		}


		public function section_with_service (Request $request)
		{
//			dd('asd');
			return SectionServices::getSectionWithServicesStatus ( $request );
		}
	}
