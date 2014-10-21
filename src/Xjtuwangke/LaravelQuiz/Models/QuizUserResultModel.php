<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/18
 * Time: 03:04
 */

namespace Xjtuwangke\LaravelQuiz\Models;

class QuizUserResultModel extends \BasicModel{

    protected $table = 'quiz_user_results';

    public static function _schema( \Illuminate\Database\Schema\Blueprint $table ){
        $table = parent::_schema( $table );
        $table->unsignedInteger( 'user_id' );
        $table->unsignedInteger( 'result_id' );
        $table->unsignedInteger( 'quiz_id' );
        return $table;
    }

    public function result(){
        return $this->hasOne( 'QuizResultModel' , 'result_id' , 'id' );
    }

}