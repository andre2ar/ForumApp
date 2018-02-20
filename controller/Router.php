<?php
require './libraries/php/functions.php';
require './model/DB.php';
class Router
{
    public $db;
    public function __construct($site_name = '')
    {
        if(!empty($site_name))
        {
            $this->site_name = $site_name;
        }
        $this->db = new DB('localhost', 'root', '', 'formapp');
    }

    private function clear_url($url){
        $ex_url = explode("/", $url);
        $final_url = [];
        foreach($ex_url as $route){
            if(!empty($route)){
                $final_url[] = $route;
            }
        }

        return $final_url;
    }

    public function route($url){
        if(isset($_POST['operation']) && !empty($_POST['operation']))
        {
            load_model('OperationsController', ['db_connection' => $this->db]);
        }else
        {
            $url = $this->clear_url($url);

            $intendend_place = end($url);

            if($intendend_place === $this->site_name || $intendend_place === 'home'){
                $questions = $this->db->get_questions();
                load_view('home', ['questions' => $questions]);
            }else if(strstr($intendend_place, 'open_question'))
            {
	            $question_id = explode("=", $intendend_place)[1];
	            $intendend_place = explode("?", $intendend_place)[0];

	            $question = $this->db->get_question($question_id);

	            load_view($intendend_place, ['question' => $question[0]]);
            }
            else
            {
                load_view($intendend_place);
            }
        }
    }
}