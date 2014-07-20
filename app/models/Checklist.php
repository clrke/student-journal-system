<?php

class Checklist extends Eloquent
{
	public $fillable = ['deadline_id', 'caption', 'done'];

	public function deadline()
	{
		return $this->belongsTo('Deadline');
	}
}