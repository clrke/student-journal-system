<?php

class Subject extends Eloquent
{
	public $fillable = ['timeline_id', 'subject'];

	public function timeline()
	{
		return $this->belongsTo('Timeline', 'timeline_id');
	}

	public function schedules()
	{
		return $this->hasMany('Schedule');
	}

	public function isScheduled($day)
	{
		foreach ($this->schedules as $schedule)
			if($schedule->day_of_week == $day)
				return true;
		return false;
	}

	public function activities()
	{
		return $this->hasMany('Activity');
	}
}