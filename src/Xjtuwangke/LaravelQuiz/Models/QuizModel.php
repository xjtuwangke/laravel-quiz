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
        $table->longText( 'desc' )->nullable();
        $table->text( 'title' )->nullable();
        $table->enum( 'type' , [ '调研' , '测试' ] )->default( '调研' );
        return $table;
    }

    public function steps(){
        return $this->hasMany( 'Xjtuwangke\LaravelQuiz\Models\QuizStepModel' , 'quiz_id' , 'id' )->orderBy( 'step' , 'asc' );
    }

    public function results(){
        return $this->hasMany( 'Xjtuwangke\LaravelQuiz\Models\QuizResultModel' , 'quiz_id' , 'id' )->orderBy( 'order' , 'asc' );
    }

    public function getStep( $step ){
        return $this->steps()->where( 'step' , $step )->first();
    }

    public function addStep( array $attributes ){
        $count = $this->steps()->count();
        $count++;
        $attributes[ 'quiz_id' ] = $this->getKey();
        $attributes[ 'step' ] = $count;
        return QuizStepModel::create( $attributes );
    }

}