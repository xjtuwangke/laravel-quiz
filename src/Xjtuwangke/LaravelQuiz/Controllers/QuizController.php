<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/21
 * Time: 15:52
 */

namespace Xjtuwangke\LaravelQuiz\Controllers;


use Xjtuwangke\LaravelQuiz\Models\QuizModel;
use Xjtuwangke\LaravelQuiz\Models\QuizUserResultModel;
use View;
use Session;
use Route;
use Input;

class QuizController extends \Controller{

    protected $quiz = null;

    protected function setupLayout(){
        $this->layout = View::make('laravel-quiz::layout.bootstrap3');
    }

    public static function registerRoutes(){
        $class = get_class();
        Route::get( 'quiz/index/{id}/{token}' , [ 'as' => 'quiz.index' , 'uses' => "{$class}@index"] );
        Route::get( 'quiz/question/{id}/{token}/{step}' , [ 'as' => 'quiz.question' , 'uses' => "{$class}@question"] );
        Route::post( 'quiz/next/{id}/{token}' , [ 'before'=>[ 'csrf' ] , 'as' => 'quiz.next' , 'uses' => "{$class}@next"] );
    }

    public function index( $id ){
        $quiz = QuizModel::find( $id );
        if( ! $quiz ){
            $this->layout->content = View::make('laravel-quiz::errors.missing');
        }
        else{
            $this->layout->content = View::make('laravel-quiz::contents.index')->with( 'title' , $quiz->title )->with( 'desc' , $quiz->desc );
        }
    }

    public function question( $user_id , $token , $step = 1 ){
        $result = QuizUserResultModel::where( 'user_id' , $user_id )->where( 'token' , $token )->first();
        if( ! $result || ! ( $quiz = $result->quiz ) ){
            $this->layout->content = View::make('laravel-quiz::errors.missing');
        }
        else{
            $step = $quiz->getStep( $step );
            if( ! $step ){
                $this->layout->content = View::make('laravel-quiz::errors.missing');
            }
            else{
                $this->layout->content = View::make('laravel-quiz::questions.form')
                    ->with( 'step' , $step )->with( 'id' , $user_id )->with( 'token' , $token )
                    ->with( 'content' , View::make('laravel-quiz::questions.' . $step->type )->with( 'step' , $step ) );
            }
        }
    }

    public function next( $user_id , $token ){
        $input = Input::all();
        $prev = Input::get( '_step' );
        
        var_dump( $input );
    }

    public static function assignTokenForQuizUser( \Eloquent $user , QuizModel $quiz ){
        $result = QuizUserResultModel::createByUserAndQuiz( $user , $quiz );
        return $result->token;
    }

}