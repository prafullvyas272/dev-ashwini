<?php

namespace App\Http\Controllers;

use App\DeviceType as AppDeviceType;
use App\Http\Requests\DeviceRegisterRequest;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\DeviceType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\DeviceOwnerDetail;
use App\Models\LeasingPeriod;
use App\DeviceTrait;

class DeviceController extends Controller
{
    use DeviceTrait;

    public function getDeviceInfo($id)
    {
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        if ($device->deviceTypeId === AppDeviceType::LEASING) { //check for leasing
            $response = $device->withoutRelations();

            $response['deviceOwnerDetails'] = $device->deviceOwnerDetails->only([
                'billineName',
                'addressCountry',
                'addressZip',
                'addressCity',
                'addressStreet',
                'vatNumber'
            ]);
            $response['leasingPeriodsComputed'] = $device->leasingPeriods;
            $currentLeasingPeriod = $device->leasingPeriods
    ->where('leasingActualPeriodStartDate', '<=', now()) // Find periods that have started
    ->sortByDesc('leasingActualPeriodStartDate') // Get the most recent one
    ->first(); // Get only the latest valid period

dd($currentLeasingPeriod);


            $response['leasingPeriods'] = $device->leasingPeriods;

            $response = $this->getListDeviceInfoResponse($response);
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
