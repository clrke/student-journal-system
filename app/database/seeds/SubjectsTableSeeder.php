<?php

class SubjectsTableSeeder extends Seeder
{
	public function run()
	{
		Subject::truncate();

		Subject::create(['timeline_id' => 1, 'subject' => 'Biological Sciences']);
		Subject::create(['timeline_id' => 1, 'subject' => 'Digital Speech and Audio Processing']);
			
	}
}