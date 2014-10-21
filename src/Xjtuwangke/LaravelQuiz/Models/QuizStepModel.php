<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/18
 * Time: 02:56
 */

namespace Xjtuwangke\LaravelQuiz\Models;

class QuizStepModel extends \BasicModel{

    protected $table = 'quiz_step';

    const Type_SingleSelect = 'single_select';

    const Type_MultiSelect = 'multi_select';

    const QuizFinished = 0;

    const QuizAborted = -1;

    public static function _schema( \Illuminate\Database\Schema\Blueprint $table ){
        $table = parent::_schema( $table );
        $table->unsignedInteger( 'quiz_id' );
        $table->unsignedInteger( 'step' );
        $table->longText( 'options' )->nullable();
        $table->longText( 'text' )->nullable();
        $table->longText( 'score' )->nullable();
        $table->longText( 'next' )->nullable();
        $table->text( 'type' )->nullable();
        $table->index( [ 'quiz_id' ] );
        return $table;
    }

    public function getOptionsAttribute( $value ){
        if( ! $value ){
            return array();
        }
        else{
            return @json_decode( $value , true );
        }
    }

    public function setOptionsAttribute( $value ){
        $this->attributes['options'] = json_encode( $value );
    }

    public function addOption( $value , $text ){
        $options = $this->options;
        $options[ $value ] = $text;
        $this->options = $options;
        $this->save();
        return $this;
    }

    public function nextStep( QuizUserResultModel $userResult ){
        if( ! $this->next ){
            $next = static::where( 'quiz_id' , $this->quiz_id )->where( 'step' , '>' , $this->step )->orderBy( 'step' , 'asc' )->first();
            if( ! $next ){
                return static::QuizFinished;
            }
            return $next;
        }
        else{
            //...
        }
    }

}