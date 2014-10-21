<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/18
 * Time: 02:54
 */

namespace Xjtuwangke\LaravelQuiz\Models;

class QuizResultModel extends \BasicModel{

    protected $table = 'quiz_results';

    public static function _schema( \Illuminate\Database\Schema\Blueprint $table ){
        $table = parent::_schema( $table );
        $table->unsignedInteger( 'quiz_id' );
        $table->integer( 'order' );
        $table->longText( 'result' );
        return $table;
    }
}