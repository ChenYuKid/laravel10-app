<?php

namespace App\Http\Middleware;

use App\Models\RequestInfo;
use Closure;
use Illuminate\Http\Request;

class RequestRecord
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
        //初始化请求id也可以由前端生成，给后续操作确定是否为同一个请求
        $requestId = uniqid('', true);
        $request->offsetSet('request_id', $requestId);

        $requestInfoModel = new RequestInfo();
        $requestInfoModel->create([
            'request_id' => $requestId,
            'method' => $request->getMethod(),
            'route' => $request->getRequestUri(),
            'request' => json_encode($request->all()),
            'response' => '',
        ]);

        return $next($request);
    }
}
