<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\CodeRepositoryInterface;
use App\DataTables\Dashboard\Admin\CodeDataTable;
use App\Models\Code;
use Illuminate\Support\Str;

class CodesController extends Controller
{
    public function __construct(protected CodeDataTable $codeDataTable, protected CodeRepositoryInterface $codeRepositoryInterface) {
        $this->codeRepositoryInterface = $codeRepositoryInterface;
        $this->codeDataTable = $codeDataTable;
    }

    public function index(CodeDataTable $codeDataTable) {
        return $this->codeRepositoryInterface->index($this->codeDataTable);
    }

    public function create()
    {
        return view('dashboard.Admin.codes.create', ['pageTitle' => trans('dashboard/admin.playlists')]);

    }

    public function store(Request $request)
    {

        $request->validate([
            'number_of_codes' => 'required|integer|min:1',
        ]);

        $numberOfCodes = $request->input('number_of_codes');
        $generatedCodes = [];

        for ($i = 0; $i < $numberOfCodes; $i++) {
            // Generate a unique random code
            do {
                $newCode = Str::random(10);
            } while (Code::where('code', $newCode)->exists());

            // Save the new code to the database
            Code::create(['code' => $newCode]);
            $generatedCodes[] = $newCode;
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', "$numberOfCodes codes have been generated successfully.");
    }
}
