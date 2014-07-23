<?php

class Answer extends Eloquent
{
	public $fillable = ['question_id', 'answer', 'correct'];

	public function question()
	{
		return $this->belongsTo('Question');
	}
}