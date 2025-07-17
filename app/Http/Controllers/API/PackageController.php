<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();

        return response()->json([
            'packages' => $packages,
            'message' => 'Packages retrieved successfully',
        ])->setStatusCode(200);
    }
    
    public function show($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'message' => 'Package not found',
            ])->setStatusCode(404);
        }

        return response()->json([
            'package' => $package,
            'message' => 'Package retrieved successfully',
        ])->setStatusCode(200);
    }

    public function store(Request $request)
    {
        // Code to create a new package
    }

    public function update(Request $request, $id)
    {
        // Code to update an existing package
    }

    public function destroy($id)
    {
        // Code to delete a package
    }
}
