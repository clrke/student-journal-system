<?php

class Timelines extends Eloquent
{
	public $fillable = ['timeline', 'start', 'end', 'flag'];

	public function subjects()
	{
		return $this->hasMany('Subjects');
	}

	public static function current()
	{
		return static::whereFlag(1)->get()->first();
	}
}