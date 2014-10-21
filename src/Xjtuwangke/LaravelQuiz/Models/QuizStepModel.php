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

    public static function _schema( \Illuminate\Database\Schema\Blueprint $table ){
        $table = parent::_schema( $table );
        $table->unsignedInteger( 'quiz_id' );
        $table->unsignedInteger( 'step' );
        $table->index( 'select' );
        $table->longText( 'content' );
        $table->longText( 'next' );
        return $table;
    }
}