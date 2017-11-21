<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Notifications\Notifiable;
	class Section extends Model
	{
		use Notifiable;
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = [
			'name_ar','name_en','desc_ar','desc_en','section_id','services_id'
		];
protected $hidden=[
	'created_at',"status",
            "deleted_at",
            "updated_at",
];
		public function services ()
		{
			return $this->belongsToMany ('App\Services')->withTimestamps ()->withPivot ('services_id','section_id');
		}
		//
	}
