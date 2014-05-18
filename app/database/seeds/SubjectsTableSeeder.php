<?php

class SubjectsTableSeeder extends Seeder
{
	public function run()
	{
		Subjects::truncate();

		Subjects::create(['timeline_id' => 1, 'subject' => 'Biological Sciences']);
		Subjects::create(['timeline_id' => 1, 'subject' => 'Digital Speech and Audio Processing']);
			
	}
}