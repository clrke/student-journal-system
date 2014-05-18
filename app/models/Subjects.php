<?php

class Subjects extends Eloquent
{
	public $fillable = ['timeline_id', 'subject'];

	public function timeline()
	{
		return $this->belongsTo('Timeline');
	}
}