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

}