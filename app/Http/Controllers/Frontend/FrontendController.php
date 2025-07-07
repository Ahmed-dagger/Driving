<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Code;
use App\Models\Course;
use Illuminate\Http\Request;

use Carbon\Carbon;

class FrontendController extends Controller {
    public function __invoke() {
        return view('frontend.home', ['pageTitle' => trans('site/site.home_page_title')]);
        
    }

    public function validateCode(Request $request)
{
    // Validate and find the code
    $request->validate([
        'code' => 'required|string',
    ]);

    session(['has_access' => false]);

    $code = Code::where('code', $request->input('code'))->first();

    if ($code && !$code->is_used && ($code->expires_at === null || Carbon::parse($code->expires_at)->isFuture())) {
        // Mark the code as used
        $code->update(['is_used' => true]);

        // Set a session variable to grant access
        session(['has_access' => true]);

        return redirect()->route('site.content');
    }
    else{
        session(['has_access' => false]);
    }

    

    return back()->withErrors(['code' => 'Invalid or expired code.']);
}

    public function showContentPage()
    {
        // Check if the user has access
        if (session('has_access')) {
            return view('frontend.content'); // Return the protected content view
        }

        // Redirect if the user doesn't have access
        return redirect('/')->withErrors(['access' => 'You need a valid code to access this page.']);
    }


}
