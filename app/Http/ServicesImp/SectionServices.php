<?php
	/**
	 * Created by PhpStorm.
	 * UserModel: dark-
	 * Date: 10/24/2017
	 * Time: 4:17 PM
	 */

	namespace App\Http\Controllers;


	use App\AdminModel;
	use App\SectionModel;
	use App\section_services;
	use App\ServicesModel;
	use Carbon\Carbon;
	use CommonValidation;
	use Illuminate\Contracts\Pagination\Paginator;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Response;

//	use Novent\Transformers\section_servicesTra;
//	use Novent\Transformers\sectionTransform;

	use SectionIntr;
	use Unit\Transformers\SectionServicesTrans;
	use Unit\Transformers\sectionTransform;
	use Unit\Transformers\servicesTransform;
	use \Validator;

	class SectionServices implements SectionIntr
	{

//		protected $statusCode = 200;

//		public function __construct (sectionTransform $userTrans , section_servicesTra $use)
//		{
//			$this->middleware ( 'auth:api' );
//			$this->userTrans = $userTrans;
//			$this->use = $use;
//
//		}

		public static function getOneSectionById ($id = null)
		{
			//get section with id to apply on services class
			$sectionObject = new SectionModel();
			$sectionObject = $sectionObject->getSectionByID ( $id );
			$objTransform = new sectionTransform();
			if ( $sectionObject ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransform->transform ( $sectionObject ) );
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}


		}


		public static function getAllSection ()
		{
			//get all section with status true
			$listSection = new SectionModel();
			$objTransformer = new sectionTransform();
			$listSection = $listSection->getSectionByStatus ( true );

			if ( !$listSection->first () ) {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTransformer->transformCollection ( $listSection->toArray () ) );
			}

		}


// cheack it if project crushed
//		public function get_one_services ($id = null)
//		{
////
//			$SectionModel = SectionModel::where ( 'id' , $id )->where ( 'status' , true )->first ();
//
//
//			if ( !$SectionModel ) {
//				return $this->respondNotFound ( 'SectionModel dose not found' );
//
//			}
//
//
//			return $this->responedFound200ForOneUser ( 'Service found' , self::success , $this->userTrans->transform ( $SectionModel ) );
//
//			//				]);
//		}

		public static function createSection (Request $request)
		{
			$admin_id = $request->input ( 'admin_id' );
			$adminObject = new AdminModel();
			$adminObject = $adminObject->getAdminByID ( $admin_id );
//			dd($findAdmin->toArray ());
			$requestErrorSection = CommonValidation::createSectionValidation ( $request );
			if ( !$requestErrorSection ) {
				if ( $adminObject ) {
					$image_path = url ( '/icons/404.png' );

					if ( $request->input ( 'image' ) ) {
						if ( file_exists ( base_path ( 'icons//' . $request->input ( 'image' ) ) ) ) {
							$image_path = url ( 'icons/' . $request->input ( 'image' ) );
						} else {
							$image_path = url ( '/icons/404.png' );
						}

					} elseif ( !$request->input ( 'image' ) )

						$image_path = url ( '/icons/404.png' );

					$sectionObject = new SectionModel();
					$objTransformer = new sectionTransform();
					$tsCurrentDate = Carbon::now ( 'GMT+2' );
					$sectionObject->name_en = $request->input ( 'name_en' );
					$sectionObject->name_ar = $request->input ( 'name_ar' );
					$sectionObject->desc_en = $request->input ( 'desc_en' );
					$sectionObject->desc_ar = $request->input ( 'desc_ar' );
					$sectionObject->status = 1;
					$sectionObject->image = $image_path;
					$sectionObject->created_at = $tsCurrentDate;
					$sectionObject->timestamps = false;

//					$sectionObject->save ();

					$adminObject->sections ()->save ( $sectionObject );

					$objResponse = new ResponseDisplay( ResponseMassage::$Success_Created_en , ResponseStatus::$success , ResponseCode::$HTTP_CREATED );

					return $objResponse->returnWithData ( $objTransformer->transform ( $sectionObject ) );
				} else {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();

				}
			} else {
				return $requestErrorSection;
			}
		}


		public static function deleteSection ($id)
		{
			// delete section with response
			$tsCurrentDate = Carbon::now ( 'GMT+2' );

			$sectionObject = new SectionModel();
			$sectionObject = $sectionObject->getSectionByID ( $id );
			if ( !$sectionObject ) {
				{
					$objResponse = new ResponseDisplay( ResponseMassage::$FAILED_Deleted_Massages_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				}
			} else {
				$sectionObject->status = 0;
				$sectionObject->deleted_at = $tsCurrentDate;
				$sectionObject->timestamps = false;
				$sectionObject->save ();

				$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Deleted_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithOutData ();
			}


		}


		public static function updateSection (Request $request , $id)
		{
			// update section info
			$requestErrorSection = CommonValidation::updateSectionValidation ( $request );
//dd('need update icon');
			$sectionObject = new SectionModel();
			$sectionObject = $sectionObject->getSectionByID ( $id );
			$tsCurrentDate = Carbon::now ( 'GMT+2' );
			if ( !$requestErrorSection ) {
				if ( !$sectionObject ) {
					$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

					return $objResponse->returnWithOutData ();
				} else {
					if ( $request->has ( 'image' ) !== $sectionObject->image ) {
						$image_path = url ( '/icons/404.png' );
						if ( $request->input ( 'image' ) ) {
							if ( file_exists ( base_path ( 'icons//' . $request->input ( 'image' ) ) ) ) {

								$image_path = url ( 'icons/' . $request->input ( 'image' ) );
							} else {
								$image_path = url ( '/icons/404.png' );
							}

						} elseif ( !$request->input ( 'image' ) )

							$image_path = $sectionObject->image;

//						DB::table ( 'services' )
//							->where ( 'id' , $id )
//							->update ( ['image' => $image_path , 'updated_at' => $now] );

					}
					$objTransformer = new sectionTransform();
					$sectionObject->update ( $request->all () );
					$sectionObject->image = $image_path;
					$sectionObject->updated_at = $tsCurrentDate;
					$sectionObject->timestamps = false;
					$sectionObject->save ();
					$objResponse = new ResponseDisplay( ResponseMassage::$SUCCESS_Update_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

					return $objResponse->returnWithData ( $objTransformer->transform ( $sectionObject ) );

				}
			} else {
				return $requestErrorSection;
			}
		}


		public static function getSectionWithServices (Request $request , $id = null)
		{
//			dd('asdqd');
			//to get section with services
			$sectionObject = new SectionModel();
			$sectionObject = $sectionObject->getSectionByID ( $id );
//			dd($servicesList);
			if ( $sectionObject ) {
//				$servicesList = $sectionObject->services;
				$objTrans = new SectionServicesTrans();

				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTrans->transform ( $sectionObject ) );
			} else {
				$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();
			}
		}

		/*public static function getSectionsWithServices (Request $request , $id)
		{
			// get sections services
			$rules = array (
				'services' => 'required|min:0|max:1' ,

			);
			$messages = array (

				'services.required' => 'services required for me || يرجى ادخال services' ,
				'services.min' => 'services must be 0 or 1 || يرجى ادخال 0 او 1' ,
				'services.max' => 'services must be 0 or 1 || يرجى ادخال 0 او 1' ,
			);

			$validator = Validator::make ( $request->all () , $rules , $messages );

			$errors = $validator->errors ();

			if ( $validator->fails () )
				if ( $errors->first ( 'services' ) )
					return $this->respondwithErrorMessage (
						self::fail , $errors->first ( 'services' ) );
			if ( $errors->first ( 'services' ) )
				return $this->respondwithErrorMessage (
					self::fail , $errors->first ( 'services' ) );

			$section_services = SectionModel::find ( $request->input ( $id ) );

			if ( !is_object ( $section_services ) and !is_array ( $section_services ) )
				return $this->respondWithError ( 'section services not found' , self::fail );
			else
				return $this->responedFound200ServicesC ( 'ServicesModel Found' , self::success ,
					$this->userTrans->transformCollection ( $section_services->services->toArray () ) );


		}*/


		public static function getSectionWithServicesStatus (Request $request)
		{
			//get all sections with services
			$serviceRequest = $request->input ( 'service' );


			$sectionList = new SectionModel();
			$sectionList = $sectionList->getSectionByStatuswithRelation ( $serviceRequest );


			if($sectionList->first ())
			{
				$objTrans = new SectionServicesTrans();

				$objResponse = new ResponseDisplay( ResponseMassage::$Success_Found_en , ResponseStatus::$success , ResponseCode::$HTTP_OK );

				return $objResponse->returnWithData ( $objTrans->transformCollection ( $sectionList->all () ) );}
			else
			{$objResponse = new ResponseDisplay( ResponseMassage::$FAIL_Not_Found_en , ResponseStatus::$fail , ResponseCode::$HTTP_BAD_REQUEST );

				return $objResponse->returnWithOutData ();}
		}


	}