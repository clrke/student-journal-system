<?php

class TimelinesTableSeeder extends Seeder
{
	public function run()
	{
		Timeline::truncate();

		Timeline::create(['timeline' => '4th year 1st sem', 'start' => '2014-06-01', 'end' => '2014-10-31', 'flag' => 1]);
	}
}