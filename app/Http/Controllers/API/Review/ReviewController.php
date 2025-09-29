<?php

namespace App\Http\Controllers\API\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewRequest;

class ReviewController extends Controller
{
    protected $reviewRepository;

    public function __construct($reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function addOrUpdateReview(ReviewRequest $request)
    {
        return $this->reviewRepository->addOrUpdateReview($request);
    }

    public function deleteReview($doctorId)
    {
        return $this->reviewRepository->deleteReview($doctorId);
    }

    public function getDoctorReviews($doctorId)
    {
        return $this->reviewRepository->getDoctorReviews($doctorId);
    }
    public function getDoctorAverageRating($doctorId)
    {
        return $this->reviewRepository->getDoctorAverageRating($doctorId);
    }


}
