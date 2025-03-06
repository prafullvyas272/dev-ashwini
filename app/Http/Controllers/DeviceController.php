<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRegisterRequest;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\DeviceType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function getDeviceInfo() {}


    public function registerDevice(DeviceRegisterRequest $request)
    {
        try {
            $deviceAPIKey = Str::random(32);
            $activationCode = Str::random(32);

            $device = Device::whereDeviceid($request->deviceData()['deviceId'])->first();

            if ($device) {
                return response()->json([
                    "deviceId" => $device->deviceId,
                    "deviceAPIKey" => $deviceAPIKey,
                    "deviceType" => $this->getDeviceTypebyId($device->deviceTypeId),
                    "timestamp" => now()->toDateTimeString()
                ]);
            }


            $device = Device::create([
                'deviceId' => $request->deviceData()['deviceId'],
                'deviceTypeId' => 1,
                'deviceAPIKey' => $deviceAPIKey,
                'activationCode' => $activationCode,
            ]);

            if ($request->input('activationCode') == null && $device) {
                $device->update([
                    'deviceTypeId' => 2 //update to free
                ]);
            }
            if ($request->input('activationCode') && $device) {
                $device->update([
                    'deviceTypeId' => 3 //update to leasing
                ]);
            }

            // Return response with device info
            return response()->json([
                "deviceId" => $device->deviceId,
                "deviceAPIKey" => $deviceAPIKey,
                "deviceType" => $this->getDeviceTypebyId($device->deviceTypeId),
                "timestamp" => now()->toDateTimeString()
            ]);
        } catch (\Throwable $exception) {
            Log::error('Error registering device' . $exception);
            return response()->json([
                'message' => 'Error registering device',
                "title" => $exception->getMessage(),
                "description" => $exception
            ]);
        }
    }

    public function getDeviceTypebyId($deviceTypeId)
    {
        return DeviceType::whereId($deviceTypeId)->first()['name'];
    }
}
