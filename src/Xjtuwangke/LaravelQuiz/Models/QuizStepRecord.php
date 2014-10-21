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
        $table->unsignedInteger( 'quiz_user_results_id' );
        $table->unsignedInteger( 'quiz_id' );
        $table->longText( 'record' )->nullable();
        return $table;
    }

}