<?php

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
		return ['original' => $value, 'formatted' => (new Carbon\Carbon($value))->toFormattedDateString(), 'diffForHumans' => (new Carbon\Carbon($value))->diffForHumans()];
	}
}