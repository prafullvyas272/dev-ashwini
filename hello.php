namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceOwnerDetail;
use App\Models\LeasingPeriod;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Get the device information by device ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviceInfo($id)
    {
        // Find the device by ID
        $device = Device::find($id);

        // If the device doesn't exist, return a 404 error
        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        // Prepare the response data
        $response = [
            'deviceId' => $device->deviceId,
            'deviceType' => $device->deviceType,
            'timestamp' => now()->toDateTimeString()
        ];

        // If the device is leased, include the leasing and owner details
        if ($device->deviceType === 'leasing') {
            // Get device owner details
            $ownerDetails = DeviceOwnerDetail::where('device_id', $device->id)->first();

            // Get leasing periods
            $leasingPeriods = LeasingPeriod::where('device_id', $device->id)->get();

            // Get leasing computed details (assuming this is a fixed computation)
            $leasingPeriodsComputed = [
                'leasingConstructionId' => 51342268,  // Sample data, you may adjust based on actual logic
                'leasingConstructionMaximumTraining' => 1000,
                'leasingConstructionMaximumDate' => '2021-06-01',
                'leasingActualPeriodStartDate' => '2021-12-01',
                'leasingNextCheck' => '2021-12-01 17:30:00',
            ];

            // Prepare the full response for a leased device
            $response['deviceOwner'] = $ownerDetails ? $ownerDetails->billing_name : null;
            $response['deviceOwnerDetails'] = $ownerDetails;
            $response['leasingPeriodsComputed'] = $leasingPeriodsComputed;
            $response['leasingPeriods'] = $leasingPeriods;
        } else {
            // If the device is not leased, leasingPeriods will be empty
            $response['leasingPeriods'] = [];
        }

        // Return the response
        return response()->json($response);
    }
}
