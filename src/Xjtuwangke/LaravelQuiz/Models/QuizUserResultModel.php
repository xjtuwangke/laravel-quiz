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
            'token'   => KRandom::getRandStr() ,
            'result_text' => '' ,
            'status' => '未开始' ,
        ));
    }

}