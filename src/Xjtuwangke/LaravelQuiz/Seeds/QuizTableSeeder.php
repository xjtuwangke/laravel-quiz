<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/21
 * Time: 21:26
 */

namespace Xjtuwangke\LaravelQuiz\Seeds;

use Xjtuwangke\LaravelQuiz\Models\QuizModel;
use Xjtuwangke\LaravelQuiz\Models\QuizStepModel;
use Xjtuwangke\LaravelSeeder\BasicTableSeeder;

class QuizTableSeeder extends BasicTableSeeder{

    protected $tables = [
        'Xjtuwangke\LaravelQuiz\Models\QuizModel' ,
    ];

    protected function seeds_model_QuizModel(){

        \DB::table( QuizStepModel::getTableName() )->delete();

        $quiz = QuizModel::create( array(
            'title' => '产品满意度调研' ,
            'desc'  => $this->fake_html5() ,
            'type' => '调研' ,
        ));
        for( $i = 0 ; $i < 5 ; $i++ ){
            $step = $quiz->addStep( array(
                'text' => $this->faker->sentence ,
                'type' => QuizStepModel::Type_SingleSelect ,
            ));
            for( $j = 0 ; $j < 4 ; $j++ ){
                $step->addOption( $j , $this->faker->sentence );
            }
        }

        $quiz = QuizModel::create( array(
            'title' => '课程满意度调研' ,
            'desc'  => $this->fake_html5() ,
            'type' => '调研' ,
        ));
        for( $i = 0 ; $i < 5 ; $i++ ){
            $step = $quiz->addStep( array(
                'text' => $this->faker->sentence ,
                'type' => QuizStepModel::Type_SingleSelect ,
            ));
            for( $j = 0 ; $j < 4 ; $j++ ){
                $step->addOption( $j , $this->faker->sentence );
            }
        }
        return null;
    }
}