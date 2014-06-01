<?php

class Deadline extends Eloquent
{
	public $fillable = ['subject_id', 'caption', 'deadline'];

	public function subject()
	{
		return $this->belongsTo('Subject');
	}

	public function getDeadlineAttribute($value)
	{
		return ['original' => (new Carbon\Carbon($value))->toFormattedDateString(), 'diffForHumans' => (new Carbon\Carbon($value))->diffForHumans()];
	}
}