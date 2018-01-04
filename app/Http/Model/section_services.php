<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class section_services extends Model
{
	protected $table= 'section_services';
	protected $fillable = [
		'section_id','services_id'
	];

	public function section_serv ()
	{

		return $this->belongsToMany (SectionModel::class,'section_services')->withTimestamps ();
	}
}
