<?php

class event
{
	public $id; //活动id
	public $title;
	public $description;
	public $start;
	public $end;

	public function __construct($event)
	{
		if (is_array($event))
		{
			$this->id = $event['event_id'];
			$this->title = $event['title'];
			$this->description = $event['description'];
			$this->start = $event['start'];
			$this->end = $event['end'];
		}
		else
		{
			throw new Exception("No event data was supplied");
		}
	}
}
?>