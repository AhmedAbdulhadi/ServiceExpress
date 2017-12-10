<?php

namespace App;

use App\Http\Controllers\UserServices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Services extends Model
{
	use Notifiable;
	public $table = "services";
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name_ar','name_en','desc_ar','desc_en','section_id','services_id','image'
	];
	protected $hidden=[
		"deleted_at",
                    "created_at",
                    "updated_at",
                    "pivot"
	];

	public function sections ()
	{
		return $this->belongsTo  (Section::class);

	}

	public function suppliers()
	{

		return $this->belongsToMany (Supplier::class,'services_suppliers')->withTimestamps ();

	}
	//


}
