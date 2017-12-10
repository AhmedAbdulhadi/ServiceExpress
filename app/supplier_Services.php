<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class supplier_Services extends Model
	{
		protected $table= 'services_suppliers';
		protected $fillable = [
			'supplier_id','services_id'
		];


	}
