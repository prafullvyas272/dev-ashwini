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
use App\Models\ActivationCode;

class DeviceController extends Controller
{
    use DeviceTrait;

    /**
     * Method to get the device info
     */
    public function getDeviceInfo($id)
    {
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        if ($device->deviceTypeId === AppDeviceType::FREE || $device->deviceTypeId === AppDeviceType::LEASING) {
            $response = $device->withoutRelations();

            $response['deviceOwnerDetails'] = $device->deviceOwnerDetails->only([
                'billingName',
                'addressCountry',
                'addressZip',
                'addressCity',
                'addressStreet',
                'vatNumber'
            ]);
            $currentLeasingPeriod = $device->leasingPeriods
                ->where('leasingActualPeriodStartDate', '<=', now())
                ->sortByDesc('leasingActualPeriodStartDate')
                ->first();
            $response['leasingPeriodsComputed'] = $currentLeasingPeriod;
            $response['leasingPeriods'] = $device->leasingPeriods;
            $response = $this->getListDeviceInfoResponse($response);
        } else {
            $response['leasingPeriods'] = [];
        }

        return response()->json($response);
    }


    /**
     * Method to register the device
     */
    public function registerDevice(DeviceRegisterRequest $request)
    {
        try {
            $deviceAPIKey = Str::random(32);
            $device = Device::whereDeviceid($request->deviceData()['deviceId'])->first();

            if (!$device) {
                return response()->json([
                    'error' => 'Device not found',
                    'description' => 'Device not exist in DB.'
                ], 404);
            }

            /**
             * Handle Case D, E
             * device does not have “activationCode“ assigned and it is already registered as “free” and it tries to register
             * with valid (existing and not already associated to another device) “activationCode”
             */
            $activationCode = $request->input('activationCode');
            $isActivationCodeValid = $this->checkIfActivationCodeValid($activationCode);

            if ($request->has('activationCode') && !$isActivationCodeValid) {
                return response()->json([
                    'error' => 'The activation code is invalid',
                    'description' => 'The activation code does not exist in DB.'
                ], 404);
            }
            if ($device && $isActivationCodeValid) {
                $this->assignActivationCode($device, $activationCode);
                ActivationCode::where('activationCode', $activationCode)->update([
                    'deviceId' => $device->id,
                ]);
                $device->update([
                    "deviceAPIKey" => $deviceAPIKey,
                    "deviceTypeId" => AppDeviceType::LEASING,
                ]);
                return response()->json([
                    "deviceId" => $device->deviceId,
                    "deviceAPIKey" => $deviceAPIKey,
                    "deviceType" => $this->getDeviceTypebyId($device->deviceTypeId),
                    "timestamp" => now()->toDateTimeString()
                ]);
            }

            $deviceType = $activationCode == null ?  AppDeviceType::FREE : AppDeviceType::LEASING;

            if ($device) {
                $device->update([
                    "deviceAPIKey" => $deviceAPIKey,
                    "deviceTypeId" => $deviceType,
                ]);
                if (!$device->activationCode) {
                    $activationCode = ActivationCode::where('deviceId', null)->first()['activationCode'];
                    $this->assignActivationCode($device, $activationCode);
                    ActivationCode::where('activationCode', $activationCode)->update([
                        'deviceId' => $device->id,
                    ]);
                }

                return response()->json([
                    "deviceId" => $device->deviceId,
                    "deviceAPIKey" => $deviceAPIKey,
                    "deviceType" => $this->getDeviceTypebyId($device->deviceTypeId),
                    "timestamp" => now()->toDateTimeString()
                ]);
            }

            /**
             * If the registration is successfully performed without the activation_code and the tablet does not have
             * activation_code associated to it - its device_type changes to free (tablet is being used without
             * leasing plan and has limited use).
             */
            if ($request->input('activationCode') == null && $device) {
                $this->updateDeviceType($device, AppDeviceType::FREE);
            }

            /**
             * If the registration is successfully performed with the activation_code - its device_type changes to
             * leasing (tablet can be used according to a leasing plan).
             * leasing: registered and activation_code assigned
             */
            if ($request->input('activationCode') && $device) {
                $this->updateDeviceType($device, AppDeviceType::LEASING);
                $this->assignActivationCode($device);
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

    /**
     * Method to get device type by id
     */
    public function getDeviceTypebyId($deviceTypeId)
    {
        return DeviceType::whereId($deviceTypeId)->first()['name'];
    }

    /**
     * Method to update the device type
     */
    public function updateDeviceType($device , $deviceType)
    {
        return $device->update([
            'deviceTypeId' => $deviceType
        ]);
    }

    /**
     * Method to assign the activation code
     */
    public function assignActivationCode($device, $activationCode = null)
    {
        return $device->update([
            'activationCode' => $activationCode ?? Str::random(30)
        ]);
    }

    /**
     * Method to assign the activation code
     */
    public function checkIfActivationCodeValid($activationCode)
    {
        return ActivationCode::where('activationCode', $activationCode)->where('deviceId', null)->exists();
    }
}
