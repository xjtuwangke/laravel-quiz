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

    public function userRecords(){
        return $this->hasMany( 'Xjtuwangke\LaravelQuiz\Models\QuizUserResultModel' , 'quiz_id' , 'id' )->where( 'status' , '完成' )->groupBy( 'user_id' )->orderBy( 'updated_at' , 'desc' );
    }

    public function statistics(){
        $results = array();
        foreach( $this->steps as $step ){
            $results[ $step->step ] = array( 'question' => $step->text );
            foreach( $step->options as $key => $val ){
                $results[ $step->step ][ $key ] = array(
                    'text' => $val ,
                    'count' => 0 ,
                );
            }
            $records = $this->userRecords;
            foreach( $records as $record ){
                $this_step = $record->getStep( $step->step );
                foreach( $this_step->selected as $selected ){
                    foreach( $results[ $step->step ] as $key => $val ){
                        if( $selected == $key ){
                            $results[ $step->step ][ $key ][ 'count' ]++;
                        }
                    }
                }
            }
        }
        return $results;
    }

}