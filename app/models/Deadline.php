<?php

use Carbon\Carbon;

class Deadline extends Eloquent
{
	public $fillable = ['subject_id', 'caption', 'deadline'];

	public function subject()
	{
		return $this->belongsTo('Subject');
	}

	public function checklists()
	{
		return $this->hasMany('Checklist');
	}

	public function getDeadlineAttribute($value)
	{
		return ['original' => $value, 'formatted' => (new Carbon($value))->toFormattedDateString(), 'diffForHumans' => static::diffForHumans($value)];
	}

	private static function diffForHumans($date)
	{
		$date = new Carbon($date);

		$diffInHours = $date->diffInHours(Carbon::now(), false);
		
		if($diffInHours <= 24)
		{
			if($diffInHours >= 0)
				return 'Today';
			else
				return $date->addDay(1)->diffForHumans();
		}
		else return $date->diffForHumans();
	}
}