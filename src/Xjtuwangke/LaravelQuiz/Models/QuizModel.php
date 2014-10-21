<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/18
 * Time: 02:48
 */

namespace Xjtuwangke\LaravelQuiz\Models;

class QuizModel extends \BasicModel{

    protected $table = 'quiz';

    public static function _schema( \Illuminate\Database\Schema\Blueprint $table ){
        $table = parent::_schema( $table );
        $table->unsignedInteger( 'club_article_id' );
        $table->text( 'background' );
        return $table;
    }

    public function steps(){
        return $this->hasMany( 'QuizStepModel' , 'quiz_id' , 'id' )->orderBy( 'step' , 'asc' );
    }

    public function results(){
        return $this->hasMany( 'QuizResultModel' , 'quiz_id' , 'id' )->orderBy( 'order' , 'asc' );
    }

}