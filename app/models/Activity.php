<?php

class Activity extends Eloquent
{
	public $fillable = ['subject_id', 'happened_at', 'activity'];

	public function subject()
	{
		return $this->belongsTo('Subject');
	}
}