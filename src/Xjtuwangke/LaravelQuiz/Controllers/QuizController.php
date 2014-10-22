<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 14/10/21
 * Time: 15:52
 */

namespace Xjtuwangke\LaravelQuiz\Controllers;


use Xjtuwangke\LaravelModels\Favorites\VisitModel;
use Xjtuwangke\LaravelQuiz\Models\QuizModel;
use Xjtuwangke\LaravelQuiz\Models\QuizStepModel;
use Xjtuwangke\LaravelQuiz\Models\QuizUserResultModel;
use View;
use Route;
use Input;

class QuizController extends \Controller{

    protected $quiz = null;

    protected $step = null;

    protected $result = null;

    protected function setupLayout(){
        $this->layout = View::make('laravel-quiz::layout.bootstrap3');
    }

    public static function registerRoutes(){
        $class = get_class();
        Route::get( 'quiz/index/q/{id}' , [ 'as' => 'quiz.index' , 'uses' => "{$class}@index"] );
        Route::get( 'quiz/question/u/{id}/t/{token}/s/{step}' , [ 'as' => 'quiz.question' , 'uses' => "{$class}@question"] );
        Route::post( 'quiz/next/u/{id}/t/{token}' , [ 'before'=>[ 'csrf' ] , 'as' => 'quiz.next' , 'uses' => "{$class}@next"] );
        Route::get( 'quiz/finished/u/{id}/t/{token}' , [ 'as' => 'quiz.finished' , 'uses' => "{$class}@finished"] );
        Route::get( 'quiz/result/u/{user_id}/q/{quiz_id}' , [ 'as' => 'quiz.result' , 'uses' => "{$class}@result"] );
    }

    public static function quiz_index_url( QuizModel $quiz ){
        return \URL::action( 'quiz.index' , [ $quiz->id ] );
    }

    public static function quiz_start_and_finish_url_array( QuizModel $quiz , \Eloquent $user ){
        $token = QuizController::assignTokenForQuizUser( $user , $quiz );
        return array(
            \URL::action( 'quiz.question' , [ $user->id , $token , 1 ] ) ,
            \URL::action( 'quiz.finished' , [ $user->id , $token ] ) ,
        );
    }

    public static function quiz_result_url( QuizModel $quiz , \Eloquent $user ){
        return \URL::action( 'quiz.result' , [ $user->id , $quiz->id ] );
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

    public function init( $user_id , $token , $step = null ){
        $result = QuizUserResultModel::where( 'user_id' , $user_id )->where( 'token' , $token )->first();
        if( ! $result || ! ( $quiz = $result->quiz ) ){
            $this->layout->content = View::make('laravel-quiz::errors.missing');
            return false;
        }
        else {
            $this->result = $result;
            $this->quiz = $quiz;
            if( ! is_null( $step ) ){
                $step = $quiz->getStep($step);
                if( ! $step ){
                    $this->layout->content = View::make('laravel-quiz::errors.missing');
                    return false;
                }
                else{
                    $this->step = $step;
                }
            }
        }
        return true;
    }

    public function question( $user_id , $token , $step ){
        if( true === $this->init( $user_id , $token , $step ) ){
            $this->layout->content = View::make('laravel-quiz::questions.form')
                ->with( 'step' , $this->step )->with( 'id' , $user_id )->with( 'token' , $token )
                ->with( 'content' , View::make('laravel-quiz::questions.' . $this->step->type )->with( 'step' , $this->step ) );
        }
    }

    public function next( $user_id , $token ){
        $step = Input::get( '_step' );
        $options = Input::get( 'options' );
        if( true === $this->init( $user_id , $token , $step ) ){
            $this->result->saveStepResult( $step , $options );
            $next = $this->result->next( $step );
            if( QuizStepModel::QuizFinished === $next ){
                return \Redirect::action( 'quiz.finished' , [ $user_id , $token ] );
            }
            elseif( QuizStepModel::QuizAborted === $next ){

            }
            else{
                return \Redirect::action( 'quiz.question' , [ $user_id , $token , $next->step ] );
            }
        }
    }

    public function result( $user_id , $quiz_id ){
        $result = QuizUserResultModel::where( 'user_id' , $user_id )->where( 'quiz_id' , $quiz_id )->orderBy( 'updated_at' , 'desc' )->first();
        if( ! $result || ! ( $quiz = $result->quiz ) ){
            $this->layout->content = View::make('laravel-quiz::errors.missing')->with( 'text' , '测试未找到或是您还没有完成测试' );
            return;
        }
        else {
            $this->result = $result;
            $this->quiz = $quiz;
            if( $quiz->type == '调研' ){
                $this->layout->content = View::make( 'laravel-quiz::contents.result_survey' )->with( 'quiz' , $this->quiz )->with( 'result' , $this->result );
            }
            else{
                $this->layout->content = View::make( 'laravel-quiz::contents.result_quiz' )->with( 'quiz' , $this->quiz )->with( 'result' , $this->result );
            }
        }
    }

    public function finished( $user_id , $token ){
        if( true === $this->init( $user_id , $token ) ){
            $this->layout->content = View::make( 'laravel-quiz::contents.finished' )->with( 'quiz' , $this->quiz )->with( 'result' , $this->result );
            $user = \UserModel::find( $user_id );
            if( $user ){
                VisitModel::add( $user , $this->quiz );
            }
        }
    }

    public static function assignTokenForQuizUser( \Eloquent $user , QuizModel $quiz ){
        $result = QuizUserResultModel::createByUserAndQuiz( $user , $quiz );
        return $result->token;
    }

}