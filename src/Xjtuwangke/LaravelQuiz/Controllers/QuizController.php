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