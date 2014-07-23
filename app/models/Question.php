<?php

class Question extends Eloquent
{
	public $fillable = ['subject_id', 'question'];

	public function subject()
	{
		return $this->belongsTo('Subject');
	}

	public function answers()
	{
		return $this->hasMany('Answer')->whereCorrect(1);
	}

	public function sabotages()
	{
		return $this->hasMany('Answer')->whereCorrect(0);
	}


}