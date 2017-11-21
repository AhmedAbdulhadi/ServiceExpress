<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 9/16/2017
	 * Time: 3:17 PM
	 */

	//namespace Novent\Transfroers;
	namespace Novent\Transformers;
	class AddressTransfomer extends Transfomer
	{

		public function transform ($address)
		{
			if ( $address['street'] == null )
				$address['street'] = "";
			if ( $address['city'] == null )
				$address['city'] = "";
			if ( $address['country'] == null )
				$address['country'] = "";
			if ( $address['building_number'] == null )
				$address['building_number'] = "";
			if ( $address['neighborhood'] == null )
				$address['neighborhood'] = "";
			if ( $address['apartment_number'] == null )
				$address['apartment_number'] = "";
			if ( $address['floor'] == null )
				$address['floor'] = "";

			return [
				'address_id' => $address['id'] ,
				'longitude' => $address['longitude'] ,
				'latitude' => $address['latitude'] ,
				'address_type'=>$address['address_type'] ,
				'street' => $address['street'] ,
				'city' => $address['city'] ,
				'country' => $address['country'] ,

				'building_number' => $address['building_number'] ,
				'neighborhood' => $address['neighborhood'] ,
				'apartment_number' => $address['apartment_number'] ,
				'floor' => $address['floor'] ,
				'status' => (boolean)$address['status'] ,
				'created_at' => date ( 'Y-m-d' , strtotime ( $address['created_at'] ) ) ,
				//	'active' => (boolean)$user['is_active'],
			];

		}

	}