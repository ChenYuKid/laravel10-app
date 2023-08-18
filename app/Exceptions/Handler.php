<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // 自定义异常处理返回json
        $this->renderable(function (ApiException $e){

            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => json_encode([]),
            ]);
        });

        // 代码异常导致捕获处理返回
        $this->renderable(function (\Exception $e){
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => json_encode([]),
            ]);
        });
    }
}
