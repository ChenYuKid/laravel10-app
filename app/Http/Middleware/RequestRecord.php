<?php

namespace App\Http\Middleware;

use App\Models\RequestInfo;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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

        //精确到天拆分表，不存在则创建
        $tableName = 'request_info_' . date('Y_m_d');
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('request_id', 32)->default('')->comment('请求id');
                $table->string('method', 255)->default('')->comment('请求参数');
                $table->string('route', 255)->default('')->comment('请求路由');
                $table->text('request')->comment('请求数据');
                $table->longText('response')->comment('响应数据');
                //此处laravel已经维护了时间字段所以不再增加创建时间
                $table->timestamps();
            });
        }

        $requestInfoModel = new RequestInfo();
        //设置为当前使用的表
        $requestInfoModel->setTable($tableName);
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
