<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/21
 * Time: 20:02
 */

namespace Xjtuwangke\LaravelQuiz\Models;


class QuizStepRecord extends \BasicModel{

    protected $table = 'quiz_step_records';

    public static function _schema( \Illuminate\Database\Schema\Blueprint $table ){
        $table = parent::_schema( $table );
        $table->unsignedInteger( 'step' );
        $table->unsignedInteger( 'quiz_user_results_id' );
        $table->unsignedInteger( 'quiz_id' );
        $table->text( 'options' )->nullable();
        $table->longText( 'text' )->nullable();
        return $table;
    }

    public static function createFromResult( QuizUserResultModel $result , $step , $options ){
        if( ! is_array( $options ) ){
            $options = explode( ' ' , $options );
        }
        $quiz = $result->quiz;
        $stepModel = $quiz->getStep( $step );
        $allOptions = $stepModel->options;
        $text = array();
        foreach( $options as $option ){
            if( array_key_exists( $option , $allOptions ) ){
                $text[ $option ] = $allOptions[ $option ];
            }
        }
        if( array_key_exists( 'text' , $options ) ){
            $text[ 'text' ] = $options['text'];
        }
        $options = implode( '' , $options );
        return static::create( array(
            'step' => $step ,
            'quiz_user_results_id' => $result->getKey() ,
            'options' => $options ,
            'quiz_id' => $result->quiz_id ,
            'text' => json_encode( $text , JSON_UNESCAPED_UNICODE ) ,
        ));
    }

    public function getSelectedAttribute( $value ){
        return  explode( ' ' , $this->options );
    }

}