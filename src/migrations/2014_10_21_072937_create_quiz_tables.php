<?php

class CreateQuizTables extends \Xjtuwangke\LaravelModels\Migration\BasicMigration {

	protected $tables = array(
		'Xjtuwangke\LaravelQuiz\Models\QuizModel' ,
		'Xjtuwangke\LaravelQuiz\Models\QuizStepModel' ,
		'Xjtuwangke\LaravelQuiz\Models\QuizUserResultModel' ,
		'Xjtuwangke\LaravelQuiz\Models\QuizStepRecord' ,
	);

}
