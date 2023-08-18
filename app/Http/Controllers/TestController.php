<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use ApiResponse;

    public function test1()
    {
        return $this->success('请求成功，请求参数：' . json_encode(request()->all()));
    }

    public function test2()
    {
        $this->failed(1000);
    }

    public function test3()
    {
        throw new \Exception('意外错误');
    }

    public function test4()
    {
        $string = request()->input('s');
    }
}
