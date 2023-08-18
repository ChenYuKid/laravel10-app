<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Illuminate\Support\Facades\Response;
use App\Exceptions\ApiException;

trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getStatusCodeWithFuzzy()
    {
        $code_prefix = intval($this->statusCode / 100);
        return $code_prefix * 100;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {

        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond($data, $header = [])
    {
        return Response::json($data, $this->getStatusCodeWithFuzzy(), $header);
    }

    /**
     * @param $status
     * @param array $data
     * @param null $code
     * @return mixed
     */
    public function status(array $data = null, $code = null)
    {

        if ($code) {
            $this->setStatusCode($code);
        }

        $status = [
            'code' => $this->statusCode,
            'message' => null,
            "data" => null,
        ];

        if (!$data) {
            $data = [];
        }

        $data = array_merge($status, $data);
        return $this->respond($data);

    }


    public function failed($code, $message = '')
    {
        throw new ApiException($message ?: config('ecode.' . $code), $code);
    }

    /**
     * @param $message
     * @param string $status
     * @return mixed
     */
    public function message($message)
    {

        return $this->status([
            'message' => $message,
            'data' => json_encode([])
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "Internal Error!")
    {

        return $this->failed($message, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "created")
    {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)
            ->message($message);

    }

    /**
     * @param $data
     * @param string $status
     * @return mixed
     */
    public function success($data, $message = '')
    {

        return $this->status(compact('data', 'message'));
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFound($message = 'Not Found!')
    {
        return $this->failed($message, Foundationresponse::HTTP_NOT_FOUND);
    }

    public function forbidden($message = 'Forbidden')
    {
        return $this->failed($message, Foundationresponse::HTTP_FORBIDDEN);
    }

    public function responds($code, $message = '', $data = [])
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            "data" => $data,
        ]);
    }
}
