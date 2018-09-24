<?php
namespace Classes\Mapper\Event;

class EventData
{

    private $id;
    private $event_id;
    private $title;
    private $place;
    private $event_date;
    private $start_time;
    private $end_time;
    private $fee;
    private $before_seven_days;
    private $before_one_day;
    private $comment;
    private $created_at;
    private $updated_at;

    public function __construct($data){
        $this->id = $data['id'];
        $this->event_id = $data['event_id'];
        $this->title = $data['title'];
        $this->place = $data['place'];
        $this->event_date = $data['event_date'];
        $this->start_time = $data['start_time'];
        $this->end_time = $data['end_time'];
        $this->fee = $data['fee'];
        $this->before_seven_days = $data['before_seven_days'];
        $this->before_one_day = $data['before_one_day'];
        $this->comment = isset($data['comment']) ? $data['comment']: "";
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
    }

    public function getId(){
        return $this->id;
    }

    public function getEventId(){
        return $this->event_id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getPlace(){
        return $this->place;
    }

    public function getEventDate(){
        return $this->event_date;
    }

    public function getEventYear(){
        return date('Y' ,strtotime($this->event_date));
    }

    public function getEventMonth(){
        return date('m' ,strtotime($this->event_date));
    }

    public function getEventDay(){
        return date('d' ,strtotime($this->event_date));
    }

    public function getEventDateDisplay(){
        return $this->getEventMonth().'月'.$this->getEventDay().'日 ('.$this->getEventWeek().')';
    }

    public function getEventWeek(){
        $week = array(
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
        );

        $weekday = date('w',strtotime($this->event_date));
        return $week[$weekday];
        
    }

    public function getStartTime(){
        return date('H:i' ,strtotime($this->start_time));
    }

    public function getEndTime(){
        return date('H:i' ,strtotime($this->end_time));
    }

    public function getFee(){
        return $this->fee;
    }

    public function getBeforeSevenDays(){
        return $this->before_seven_days;
    }

    public function getBeforeOneDay(){
        return $this->before_one_day;
    }

    public function getComment(){
        return $this->comment;
    }

}