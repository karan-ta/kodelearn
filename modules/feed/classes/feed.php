<?php defined('SYSPATH') or die('No direct script access.');

abstract class Feed {
	
	protected $id;
	
	protected $type;
	
	protected $action;
	
	protected $respective_id;
	
	protected $course_id;
	
	protected $actor_id;
	
	public function get_actor_id(){
	  return $this->actor_id;
	}
	
	public function set_actor_id($value){
	  $this->actor_id = $value;
	}
	   
	 
	public function get_course_id(){
	  return $this->course_id;
	}
	
	public function set_course_id($value){
	  $this->course_id = $value;
	}
	   
	 
	public function get_respective_id(){
	  return $this->respective_id;
	}
	
	public function set_respective_id($value){
	  $this->respective_id = $value;
	}
	   
	 
	public function get_action(){
	  return $this->action;
	}
	
	public function set_action($value){
	  $this->action = $value;
	}
	   
	 
	public function get_type(){
	  return $this->type;
	}
	
	public function set_type($value){
	  $this->type = $value;
	}
	   
	 
	public function get_id(){
	  return $this->id;
	}
	
	public function set_id($value){
	  $this->id = $value;
	}
	
	public function load($id){
        $feed = ORM::factory('feed', $id);
        
        $this->id = $id;
        $this->type = $feed->type;
        $this->action = $feed->action;
        $this->respective_id  = $feed->respective_id;
        $this->actor_id  = $feed->actor_id;
        $this->course_id = $feed->course_id;
	}
	
	public static function factory($type, $id = NULL){
		
        $file = MODPATH . $type . '/classes/feed/' . $type . '.php';        
        if(file_exists($file)){
            $class = 'Feed_' . $type;
            return new $class($id);
        } else {
            throw new Exception('Class Feed_ ' . $type . ' not found');
        }
		
	}
	
	public function render(){
		return $this->type . ' ' . $this->action;
	}
	
}