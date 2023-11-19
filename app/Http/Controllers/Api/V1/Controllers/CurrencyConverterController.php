<?php

namespace App\Http\Controllers\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\CurrencyConverterService;

class CurrencyConverterController extends Controller
{
    protected $currencyConverterService;

    public function __construct(CurrencyConverterService $currencyConverterService)
    {
        $this->currencyConverterService = $currencyConverterService;
    }

    public function getCountriesMoney()
    {
        $getCountriesMoney = $this->currencyConverterService->getCountriesMoney();
        return response()->json($getCountriesMoney['data'], $getCountriesMoney['status']);
    }

    public function currencyConvert(Request $request)
    {
        $getCountriesMoney = $this->currencyConverterService->currencyConvert($request['userConnect'], $request['amount'], $request['fromCountry'], $request['toCountry']);
        return response()->json($getCountriesMoney['data'], $getCountriesMoney['status']);
    }
}
