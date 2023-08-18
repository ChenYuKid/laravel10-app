<?php

namespace App\Http\Middleware;

use App\Models\RequestInfo;
use Closure;
use Illuminate\Http\Request;

class ResponseRecord
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        //已经在前置中间件中初始化request_id，可以用这个判断是否为同一个请求
        $requestId = $request->input('request_id');
        $responseData = [
            'response' => json_encode([
                'response' => $response->getContent() ?: '',
                'status' => $response->getStatusCode(),
            ])
        ];

        $requestInfoModel = new RequestInfo();
        //此处也需要使用最新的表
        $tableName = 'request_info_' . date('Y_m_d');
        $requestInfoModel->setTable($tableName);
        $requestInfoModel->where('request_id', $requestId)->update($responseData);

        return $response;
    }
}
