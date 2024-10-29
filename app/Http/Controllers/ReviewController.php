<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Review;
use App\Models\ReviewReply;
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
    public function update(Review $review,Request $request)
    {
        $request->validate([
            'description' => 'required',
            'rate' => 'required'
        ]);

        $review->description = $request->description;
        $review->rate = $request->rate;
        $review->edited = true;
        $review->save();
        return $review;
    }
    public function reply(Request $request)
    {
        $request->validate([
            'review_id' => 'required',
            'description' => 'required'
        ]);

        $reply =  ReviewReply::create([
            'description' => $request->description,
            'review_id' => $request->review_id
        ]);
        return $reply;
    }
    public function updateReply(ReviewReply $reviewReplay, Request $request)
    {
        $request->validate([
            'description' => 'required'
        ]);

        $reviewReplay->description = $request->description;
        $reviewReplay->save();
        return $reviewReplay;
    }
    public function delete(Review $review)
    {
        $review->delete();
        return 'ok';
    }

    public function report(Review $review){
        $review->reported = true;
        $review->save();

        return $review;
    }
}
