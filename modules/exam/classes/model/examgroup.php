<?php defined('SYSPATH') or die('No direct script access.');

class Model_Examgroup extends ORM {

    protected $_has_many = array('exam' => array('model' => 'exam'));
    
    public function validator($data) {
        return Validation::factory($data)
            ->rule('name', 'not_empty')
            ->rule('name', 'min_length', array(':value', 3));
    }

    /**
     * MEthod to get all exams under this examgroup
     * @param int $examgroup_id
     * @return Database_MySQL_Result $result
     */
    public static function get_exams($examgroup_id) {
        $exams = ORM::factory('exam')
            ->where('examgroup_id', ' = ', $examgroup_id)
            ->find_all();
        return $exams;
    }

    public static function has_exams($examgroup_id) {
        $count = ORM::factory('exam')
            ->where('examgroup_id', ' = ', $examgroup_id)
            ->count_all();
        return ($count > 0);
    }

    /**
     * Method to get all users for an examgroup
     * @param int $examgroup_id
     * @param string $return_type (array|object)
     * @return array eg. (array(10 => 'Students Name') | array of user objects)
     */
    public static function get_students($examgroup_id, $return_type = 'array') {
        $exams = self::get_exams($examgroup_id);
        // handle the cas_e that no exam is added to this exam group yet
        if (!$exams->as_array()) {
            // echo 'No exams found for the examgroup #' . $examgroup_id;
            return array();
        } 
        $course_assoc = $exams->as_array('id', 'course_id');
        // get the courses
        $courses = ORM::factory('course')
            ->where('id', ' IN ', array_values($course_assoc))
            ->find_all();
        $students = array();
        $students_object = array();
        foreach ($courses as $course) {
            $users = Model_Course::get_students($course);
            $course_students = array();
            foreach ($users as $user) {
                $course_students[$user->id] = $user->firstname . ' ' . $user->lastname;
	            $students_object[] = $user;
            }
            // concat arrays with students instead of array_merge
            // so that re-indexing doesn't happen
            $students = $students + $course_students;
        }
        if($return_type == 'array'){
	        return $students;
        } 
        return $students_object;
    }

    /**
     * Method to get examresults for all exams coming under this
     * exam group
     * @param int $examgroup_id
     * @return array $examresults array('user_ids => array(exam_ids => marks))
     */
    public static function get_results($examgroup_id) {
        $exams = self::get_exams($examgroup_id);
        // handle the cas_e that no exam is added to this exam group yet
        $exam_id_arr = $exams->as_array('id');
        if (!$exam_id_arr) {
            // echo 'No exams found for the examgroup #' . $examgroup_id;
            return array();
        }
        $examresults = ORM::factory('examresult')
            ->where('exam_id', ' IN ', array_keys($exam_id_arr))
            ->find_all();
        $results = array();
        if ($examresults) {
            foreach ($examresults as $modelobj) {
                $user_id = $modelobj->user_id;
                $exam_id = $modelobj->exam_id;
                $marks = $modelobj->marks;
                if (!isset($results[$user_id])) {
                    $results[$user_id] = array();
                }
                $results[$user_id][$exam_id] = $marks;
            }
        } 
        return $results;
    }

    public function __toString() {
        return ucfirst($this->name);
    }

    /**
     * Method to return an anchor tag with exam name the text and 
     * link to the exam details page
     */
    public function toLink() {
        if (Acl::instance()->is_allowed('exam_edit')) {
            $url = Url::site('examgroup/edit/id/'.$this->id);
        } else {
            $url = Url::site('examgroup');
        }
        return Html::anchor($url, (string) $this);
    }
    
    public function get_ExamGroupPercent(){
        $user = Auth::instance()->get_user();
            $examresult = ORM::factory('examresult');
            $examresult->select('examgroups.*','exams.total_marks','exams.passing_marks');
            $examresult->join('exams','left')
                        ->on('exams.id','=','exam_id')
                        ->join('examgroups','left')
                        ->on('examgroups.id','=','exams.examgroup_id')
                        ->where('examresults.user_id','=',$user->id)
                        ->where('examgroups.id','=',$this->id)
                        ;
            $examresults = $examresult->find_all();
            $name = $this->name;

            $total_marks=0;
            $passing_marks=0;
            $marks=0;
            foreach($examresults as $examresult){
                $total_marks = $total_marks + $examresult->total_marks;
                $passing_marks = $passing_marks + $examresult->passing_marks;
                $marks = $marks + $examresult->marks;
            }
            $percent = ($marks)?($marks/$total_marks)*100:0;
            $passing_percent = ($passing_marks)?($passing_marks/$total_marks)*100:0;
            $result= array(
                'name'               => $name,
                'percent'            => $percent,
                'passing_percent'    => $passing_percent
            );

            return $result;
    }
}