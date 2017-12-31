<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
class login extends Authenticatable
{
	use  Notifiable,HasApiTokens;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'phone','type','status'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password'
	];
	private function setEmail($value)
	{
		$this->attributes['email']=$value;
	}

	private function setPassword($value)
	{
		$this->attributes['password']=$value;
	}

	private function setStatus($value)
	{
		$this->attributes['status']=$value;
	}
	public function getEmail()
	{
		return $this->email;
	}

	public function getPassword()
	{
		return $this->password;
	}
	public function getUserByEmail($email)
	{
		$userObject = new login();
		$userObject= $userObject->where ('email',$email)->first ();
		return $userObject;
	}
}
