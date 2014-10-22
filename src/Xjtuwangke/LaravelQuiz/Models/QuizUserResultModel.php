<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/18
 * Time: 03:04
 */

namespace Xjtuwangke\LaravelQuiz\Models;

use Xjtuwangke\Random\KRandom;

class QuizUserResultModel extends \BasicModel{

    protected $table = 'quiz_user_results';

    public static function _schema( \Illuminate\Database\Schema\Blueprint $table ){
        $table = parent::_schema( $table );
        $table->unsignedInteger( 'quiz_id' );
        $table->unsignedInteger( 'user_id' );
        $table->unsignedInteger( 'current_step' )->default( 0 );
        $table->text( 'token' )->nullable();
        $table->enum( 'status' , [ '未开始' , '进行中' , '完成' , '中止' ] );
        $table->longText( 'result_text' )->nullable();
        $table->longText( 'score' )->nullable();
        $table->index( [ 'user_id' , 'quiz_id' ] );
        return $table;
    }

    public function quiz(){
        return $this->hasOne( 'Xjtuwangke\LaravelQuiz\Models\QuizModel' , 'id' , 'quiz_id' );
    }

    public static function createByUserAndQuiz( \Eloquent $user , QuizModel $quiz ){
        return static::create( array(
            'user_id' => $user->getKey() ,
            'quiz_id' => $quiz->getKey() ,
            'token'   => time() . KRandom::getRandStr( 8 ) ,
            'result_text' => '' ,
            'status' => '未开始' ,
        ));
    }

    public function next( $step ){
        $this->current_step = $step;
        $next = $this->quiz->getStep( $step )->nextStep( $this );
        if( QuizStepModel::QuizFinished === $next ){
            $this->status = '完成';
        }
        elseif( QuizStepModel::QuizAborted === $next ){
            $this->status = '中止';
        }
        else{
            $this->status = '进行中';
        }
        $this->save();
        return $next;
    }

    public function saveStepResult( $step , $options ){
        return QuizStepRecord::createFromResult( $this , $step , $options );
    }

    public function steps(){
        return $this->hasMany( 'Xjtuwangke\LaravelQuiz\Models\QuizStepRecord' , 'quiz_user_results_id' , 'id' );
    }

    public function getStep( $step ){
        return $this->steps()->where( 'step' , $step )->first();
    }

}