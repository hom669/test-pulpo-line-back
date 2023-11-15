<?php

namespace App\Repositories;

use App\Models\AuditUserCurrencyConverter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CurrencyConverterRepository
{
  public function getCountriesMoney()
  {
    try {
      $apiKey = env('CURRENCY_ACCESS_KEY_ID');
      $url = env('CURRENCY_URL');

      $response = Http::get($url . '/countries?apiKey=' . $apiKey);

      // Verificar si la solicitud fue exitosa (código de estado 2xx)
      if ($response->successful()) {
        $data = $response->json();
        $dataResponse['data'] = $data['results'];
        $dataResponse['status'] =  $response->status();
        // Manejar los datos obtenidos de la API
        return $dataResponse;
      } else {
        // Manejar el caso en el que la solicitud no fue exitosa
        return ['error' => 'Error en la solicitud a la API', 'status' => $response->status()];
      }
    } catch (\Exception $e) {
      // Manejar excepciones, como errores de conexión, etc.
      return ['error' => $e->getMessage(), 'status' => 500];
    }
  }

  public function currencyConvert($amount, $fromCountry, $toCountry)
  {
    try {
      $apiKey = env('CURRENCY_ACCESS_KEY_ID');
      $url = env('CURRENCY_URL');
      $fromCurrency = urlencode($fromCountry);
      $toCurrency = urlencode($toCountry);
      $queryTo =  "{$fromCurrency}_{$toCurrency}";
      $queryFrom =  "{$toCurrency}_{$fromCurrency}";

      // $response = Http::get($url."/convert?q={$query}&compact=ultra&apiKey={$apiKey}");
      $responseTo = file_get_contents("{$url}/convert?q={$queryTo}&compact=ultra&apiKey={$apiKey}");
      $responseFrom = file_get_contents("{$url}/convert?q={$queryFrom}&compact=ultra&apiKey={$apiKey}");
      $total = $this->getCurrencyConverterValue($responseTo, $amount, $queryTo);
      $totalOneTo = $this->getCurrencyConverterValue($responseTo, 1, $queryTo);
      $totalOneFrom = $this->getCurrencyConverterValue($responseFrom, 1, $queryFrom);
      $data['value_convert'] = $total;
      $data['value_one_to'] = $totalOneTo;
      $data['value_one_from'] = $totalOneFrom;
      $data['status'] = 200;

      return $data;
    } catch (\Exception $e) {
      // Manejar excepciones, como errores de conexión, etc.
      return ['error' => $e->getMessage(), 'status' => 500];
    }
  }

  public function getCurrencyConverterValue($response, $amount, $query)
  {
    $obj = json_decode($response, true);
    $val = floatval($obj["$query"]);
    $total = $val * $amount;
    return number_format($total, 2, '.', '');
  }

  public function countConsultsByUser($user)
  {
    $userConsultsCount = AuditUserCurrencyConverter::where('user_id', $user)
      ->whereDate('created_at', Carbon::today())
      ->count();
    return $userConsultsCount;
  }

  public function createCurrentConverterAudit($user)
  {
    // Crear una nueva instancia del modelo
    $newAuditRecord = new AuditUserCurrencyConverter();

    // Asignar valores a los campos del modelo
    $newAuditRecord->user_id = $user;

    // Otras asignaciones si es necesario

    // Guardar el nuevo registro en la base de datos
    $newAuditRecord->save();

    // Puedes devolver alguna respuesta si es necesario
    return [
      'message' => 'Registro creado exitosamente',
      'record' => $newAuditRecord,
    ];
  }
}
