<?php

namespace App\Services;

use App\Repositories\CurrencyConverterRepository;
use Illuminate\Support\Facades\Auth;

class CurrencyConverterService
{
    protected $currencyConverterRepository;

    public function __construct(CurrencyConverterRepository $currencyConverterRepository)
    {
        $this->currencyConverterRepository = $currencyConverterRepository;
    }

    public function getCountriesMoney()
    {
        $countriesMoney = $this->currencyConverterRepository->getCountriesMoney();
        if (!isset($countriesMoney['error'])) {
            $countriesResponse = $this->createResponseCountries($countriesMoney);
        } else {
            $countriesResponse = $this->createResponseError($countriesMoney);
        }
        return $countriesResponse;
    }

    public function currencyConvert($user, $amount, $fromCountry, $toCountry)
    {
        if ($this->currencyConverterRepository->countConsultsByUser($user) < 5) {
            $currencyConvert = $this->currencyConverterRepository->currencyConvert($amount, $fromCountry, $toCountry);
            if (!isset($countriesMoney['error'])) {
                $currencyConvertResponse = $this->createResponseCurrentConverter($currencyConvert);
                $currencyConvertCreateAudit = $this->currencyConverterRepository->createCurrentConverterAudit($user);
            } else {
                $currencyConvertResponse = $this->createResponseError($currencyConvert);
            }
            return $currencyConvertResponse;
        }

        $currencyConvertCreateAuditResponse['data'] = ['error' => 'Usuario Completo sus Intentos de Conversion del Día, lo esperamos Mañana Nuevamente'];
        $currencyConvertCreateAuditResponse['status'] = 500;

        return $currencyConvertCreateAuditResponse;
    }

    public function createResponseCountries($countries)
    {
        $arrayCountries['data'] = collect($countries['data'])->map(function ($country, $key) {
            return [
                'id' => $country['currencyId'],
                'name' => $country['currencyId'] . ' - ' . $country['currencyName'],
                'avatar' => 'https://flagcdn.com/48x36/' . strtolower($country['id']) . '.png',
                'symbol' => $country['currencySymbol'],
                'currency_name' => $country['currencyName'],
                'country_name' => $country['name'],
            ];
        })->values()->all();

        $arrayCountries['status'] = $countries['status'];

        return $arrayCountries;
    }

    public function createResponseError($error)
    {
        $arrayResponse['data']['error'] = $error['error'];

        $arrayResponse['status'] = $error['status'];

        return $arrayResponse;
    }

    public function createResponseCurrentConverter($valueConverter)
    {
        $arrayCountries['data'] = $valueConverter;

        $arrayCountries['status'] = $valueConverter['status'];

        return $arrayCountries;
    }
}
