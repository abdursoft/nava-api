<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DepositBonusController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PaymentTransactionController;
use App\Http\Controllers\ReferController;
use App\Http\Controllers\ReferHistoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDepositBonusController;
use App\Http\Controllers\UserTurnOverController;
use App\Http\Middleware\AdminAuthentication;
use App\Http\Middleware\UserAuthentication;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function(){

    Route::prefix('client')->group(function(){
        Route::get('agents/{type?}', [AgentController::class,'show']);
        Route::get('deposit-bonus/{id?}', [DepositBonusController::class,'show']);
        Route::get('category/{id?}', [CategoryController::class, 'show']);
        Route::get('best-game/{id?}', [AdminGameController::class, 'show']);
    });


    Route::prefix('games')->controller(GameController::class)->group(function(){
        Route::get('/', [GameController::class,'gameList']);
        Route::get('category/{category}',[GameController::class,'gameCategory']);
        Route::get('provider/{provider}',[GameController::class,'gameProvider']);
        Route::get('category-provider/{provider}/{category}',[GameController::class,'gameProviderCategory']);
        Route::get('popular',[GameController::class,'gamePopular']);
        Route::get('round/{id}', [GameController::class, 'gameRound']);
        Route::post('transaction}', [GameController::class, 'gameTransaction']);
    });

    Route::prefix('auth')->controller(AuthController::class)->group(function(){
        Route::post('login', 'login');
        Route::post('register', 'signup');
    });

    Route::prefix('users')->middleware([UserAuthentication::class])->group(function(){
        Route::get('game/history', [GameController::class, 'gameHistory']);
        Route::get("game/play/{id}", [GameController::class, 'gamePlay']);
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('password', [PasswordController::class, 'passwordReset']);
        Route::post('deposit', [PaymentTransactionController::class, 'deposit']);
        Route::post('withdraw', [PaymentTransactionController::class, 'withdraw']);
        Route::get('bonus/{id?}', [UserDepositBonusController::class, 'show']);
        Route::get('transactions/{id?}', [PaymentTransactionController::class, 'show']);
        Route::get('turnover/{id?}', [UserTurnOverController::class, 'show']);
        Route::get('refer', [ReferController::class, 'show']);
        Route::get('refer-history', [ReferHistoryController::class, 'show']);
    });


    Route::prefix('admin')->group(function(){
        Route::post('login', [AuthController::class,'login']);

        Route::middleware([AdminAuthentication::class])->group(function(){
            Route::prefix('agent')->group(function(){
                Route::get('/', [AgentController::class, 'adminAgents']);
                Route::post('create', [AdminController::class, 'createAgent']);
            });

            Route::prefix('category')->group(function(){
                Route::get('{id?}', [CategoryController::class, 'show']);
                Route::post('create', [CategoryController::class, 'store']);
                Route::post('update/{id}', [CategoryController::class, 'update']);
                Route::delete('delete/{id}', [CategoryController::class, 'destroy']);
            });

            Route::prefix('game')->group(function(){
                Route::get('{id?}', [AdminGameController::class, 'show']);
                Route::post('create', [AdminGameController::class, 'store']);
                Route::post('update/{id}', [AdminGameController::class, 'update']);
                Route::delete('delete/{id}', [AdminGameController::class, 'destroy']);
            });


            Route::prefix('deposit-bonus')->controller(DepositBonusController::class)->group(function(){
                Route::get('{id?}', 'show');
                Route::post('create', 'store');
                Route::post('update/{id}', 'update');
                Route::delete('delete/{id}','destroy');
            });


            /**
             * Single routes
             */
            Route::get('users/{id?}', [UserController::class, 'getUser']);
            Route::get('refer/{id?}', [UserController::class, 'userRefer']);
            Route::get('transactions/{id?}', [UserController::class, 'userTransaction']);
            Route::post('users-status', [UserController::class, 'getUser']);
            Route::post('site-statistics', [AdminController::class, 'statistics']);
        });
    });
});
