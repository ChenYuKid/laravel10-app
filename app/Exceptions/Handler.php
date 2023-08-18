<?php

namespace App\Exceptions;

use App\Models\ErrorInfo;
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
        $appDebug = env('APP_DEBUG');

        //出现异常记录异常信息，如果开启debug时返回异常信息
        $this->reportable(function (Throwable $e) use ($appDebug) {

            $errorInfoModel = new ErrorInfo();
            $errorData = [
                'request_id' => request()->input('request_id'),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ];
            $errorInfoModel->create($errorData);
            if ($appDebug) {
                return response()->json($errorData);
            }
        });

        // 自定义错误返回处理
        $this->renderable(function (ApiException $e){

            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => json_encode([]),
            ]);
        });

        // 代码异常导致捕获处理返回，但未开启debug所以返回内部错误
        $this->renderable(function (\Exception $e) use ($appDebug){

            if (!$appDebug) {
                return response()->json([
                    'code' => '500',
                    'message' => '内部服务器错误',
                    'data' => json_encode([]),
                ]);
            }
        });
    }
}
