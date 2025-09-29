<?php

namespace App\Repositories\Review;

use App\Models\Doctor;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewRepository
{
    
    public function addOrUpdateReview($request)
    {
        $user = Auth::user();

        $review = Review::where('user_id', $user->id)
                        ->where('doctor_id', $request->doctor_id)
                        ->first();

        if ($review) {
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            return response()->json(["message" => "Review updated", "review" => $review]);
        }

        $newReview = Review::create([
            'user_id'   => $user->id,
            'doctor_id' => $request->doctor_id,
            'rating'    => $request->rating,
            'comment'   => $request->comment,
        ]);

        return response()->json(["message" => "Review added", "review" => $newReview]);
    }

    public function deleteReview($doctorId)
    {
        $user = Auth::user();

        $review = Review::where('user_id', $user->id)
                        ->where('doctor_id', $doctorId)
                        ->firstOrFail();

        $review->delete();

        return response()->json(["message" => "Review deleted"]);
    }

    public function getDoctorReviews($doctorId)
    {
        $reviews = Review::where('doctor_id', $doctorId)->with('user')->get();

        return response()->json($reviews);
    }

    public function getDoctorAverageRating($doctorId)
    {
        $average = Review::where('doctor_id', $doctorId)->avg('rating');

        return response()->json(["doctor_id" => $doctorId, "average_rating" => round($average, 2)]);
    }
}

