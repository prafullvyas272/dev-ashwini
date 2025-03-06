<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRegisterRequest;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\DeviceType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\DeviceOwnerDetail;
use App\Models\LeasingPeriod;

class DeviceController extends Controller
{
    public function getDeviceInfo($id)
    {
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $response = [
            'deviceId' => $device->deviceId,
            'deviceType' => $device->deviceType,
            'timestamp' => now()->toDateTimeString()
        ];

        if ($device->deviceType === 'leasing') {
            $ownerDetails = DeviceOwnerDetail::where('device_id', $device->id)->first();
            $leasingPeriods = LeasingPeriod::where('device_id', $device->id)->get();
            $leasingPeriodsComputed = [
                'leasingConstructionId' => 51342268,
                'leasingConstructionMaximumTraining' => 1000,
                'leasingConstructionMaximumDate' => '2021-06-01',
                'leasingActualPeriodStartDate' => '2021-12-01',
                'leasingNextCheck' => '2021-12-01 17:30:00',
            ];
            $response['deviceOwner'] = $ownerDetails ? $ownerDetails->billing_name : null;
            $response['deviceOwnerDetails'] = $ownerDetails;
            $response['leasingPeriodsComputed'] = $leasingPeriodsComputed;
            $response['leasingPeriods'] = $leasingPeriods;
        } else {
            $response['leasingPeriods'] = [];
        }

       
        return response()->json($response);
    }


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
