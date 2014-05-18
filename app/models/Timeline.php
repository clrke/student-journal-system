<?php

class Timeline extends Eloquent
{
	public $fillable = ['timeline', 'start', 'end', 'flag'];

	public function subjects()
	{
		return $this->hasMany('Subject');
	}

	public function schedules()
	{
		return $this->hasManyThrough('Schedule', 'Subject')->orderBy('ordinal');
	}

	public static function current()
	{
		return static::whereFlag(1)->get()->first();
	}
}