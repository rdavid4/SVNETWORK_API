<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\User;
use App\Notifications\NewReviewNotification;
use App\Notifications\ReportedReviewNotification;
use Exception;
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

        try{
            $company = Company::findOrFail($request->company_id);
            $users = $company->users;
            if ($users) {
                foreach ($users as $key => $user) {
                    $user->link = config('app.app_url') . '/user/companies/profile';
                    $user->notify(new NewReviewNotification($user));
                }
            }

        } catch (Exception $e) {
            return $e;
        }

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
        $company = $review->company;
        $admins = User::where('is_admin', 1)->get();
        $link = config('app.app_url') . '/companies/' . $company->slug;
        $company->link = $link;
        $company->review = $review->description;
        $review->reported = true;
        $review->save();
        foreach ($admins as $user) {
            $user->notify(new ReportedReviewNotification($company));
        }


        return 'Report Success';
    }
}
