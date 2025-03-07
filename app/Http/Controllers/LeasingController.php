<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateLeasingRequest;
use App\Models\LeasingPeriod;

class LeasingController extends Controller
{
    public function updateLeasing(UpdateLeasingRequest $request, $id)
    {
        try {
            LeasingPeriod::whereId($id)->update([
                'leasingConstructionMaximumTraining' => $request->input('deviceTrainings')
            ]);

            return response()->json([
                'title' => 'SUCCESS',
                'description' => 'Data updated sucessfully'
            ]);
        } catch (\Throwable $exception) {
            Log::error('Error registering device' . $exception);
            return response()->json([
                'message' => 'Error updating the data',
                "title" => $exception->getMessage(),
                "description" => $exception
            ]);
        }

    }
}
