<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'company_id' => 'required',
            'rate' => 'required'
        ]);

        $review = Review::where('user_id', auth()->id())->where('company_id', $request->company_id)->first();
        if ($review) {
            abort(422, 'Review does exist for this company');
        }
        $review =  Review::create([
            'description' => $request->description,
            'company_id' => $request->company_id,
            'user_id' => auth()->id(),
            'rate' => $request->rate
        ]);

        return $review;
    }

    public function report(Review $review){
        $review->reported = true;
        $review->save();

        return $review;
    }
}
