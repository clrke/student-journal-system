<?php

class Schedule extends Eloquent
{
	public $fillable = ['subject_id', 'day_of_week', 'ordinal'];

	public function subject()
	{
		return $this->belongsTo('Subject')->with('activities');
	}
}