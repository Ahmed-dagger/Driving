<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CarDetail;
use Illuminate\Http\Request;

class CarController extends Controller
{
     public function store(Request $request)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'car_make' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:car_details',
            'car_image' => 'nullable|image|max:2048',
        ]);

        $carDetail = CarDetail::create($validated);

        if ($request->hasFile('car_image')) {
            $carDetail->addMediaFromRequest('car_image')->toMediaCollection('car_images');
        }

        return response()->json($carDetail, 201);
    }

    public function show(CarDetail $carDetail)
    {
        $carDetail->load('instructor');
        return response()->json($carDetail);
    }

    // PUT /cars/details/{carDetail}
    public function update(Request $request, CarDetail $carDetail)
    {
        $validated = $request->validate([
            'instructor_id' => 'sometimes|exists:users,id',
            'car_make' => 'sometimes|string|max:255',
            'car_model' => 'sometimes|string|max:255',
            'plate_number' => 'sometimes|string|max:255|unique:car_details,plate_number,' . $carDetail->id,
            'car_image' => 'nullable|image|max:2048',
        ]);

        $carDetail->update($validated);

        if ($request->hasFile('car_image')) {
            $carDetail->clearMediaCollection('car_images');
            $carDetail->addMediaFromRequest('car_image')->toMediaCollection('car_images');
        }

        return response()->json($carDetail);
    }

    public function destroy(CarDetail $carDetail)
    {
        $carDetail->delete();
        return response()->json(['message' => 'Car detail deleted successfully.']);
    }
}
