<?php
require './libraries/php/functions.php';
require './model/DB.php';
class Router
{
    public $db;
    public function __construct($site_name = '', $dbOptions)
    {
        if(!empty($site_name))
        {
            $this->site_name = $site_name;
        }
        $this->db = new DB($dbOptions['dbHost'], $dbOptions['dbUser'], $dbOptions['dbPassword'], $dbOptions['dbName']);
    }

    private function clearUrl($url){
        $exUrl = explode("/", $url);
        $finalUrl = [];
        foreach($exUrl as $route){
            if(!empty($route)){
                $finalUrl[] = $route;
            }
        }

        return $finalUrl;
    }

    public function route($url){
        if(isset($_POST['operation']) && !empty($_POST['operation']))
        {
            loadModel('OperationsController', [ 'dbConnection' => $this->db]);
        }else
        {
            $url = $this->clearUrl($url);

            $intendendPlace = end($url);

            if($intendendPlace === $this->site_name || $intendendPlace === 'home' || empty($intendendPlace)){
                $questions = $this->db->getQuestions();
                loadView('home', [ 'questions' => $questions]);
            }else if(strstr($intendendPlace, 'open_question'))
            {
	            $questionId = explode("=", $intendendPlace)[1];
	            $intendendPlace = explode("?", $intendendPlace)[0];

	            $question = $this->db->getQuestion($questionId);
	            $answers = $this->db->getAnswers($questionId);

	            loadView($intendendPlace, [ 'question' => $question[0], 'comments' => $answers]);
            }
            else
            {
                loadView($intendendPlace);
            }
        }
    }
}