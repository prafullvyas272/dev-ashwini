<?php

namespace App;

use App\Models\DeviceType;
use Carbon\Carbon;

trait DeviceTrait
{
    public function getListDeviceInfoResponse($response)
    {
        unset($response['id']);
        unset($response['updated_at']);

        $response['deviceType'] = DeviceType::whereId($response['deviceTypeId'])->first()['name'];
        $response['dateofRegistration'] = Carbon::parse($response['created_at'])->format('Y-m-d H:i:s');
        $response['timestamp'] = Carbon::parse($response['created_at'])->format('Y-m-d H:i:s');

        unset($response['created_at']);
        unset($response['deviceTypeId']);
        unset($response['deviceOwnerId']);

        return $response;
    }

    public function sendRegistrationFailedResponse()
    {
        $data = [
            "title" =>  "Device Registration failed",
            "description" =>  "Device Id not found"
        ];
        return response()->json($data, 422);
    }
}
