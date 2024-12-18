<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    // This method show review list form db
    public function ReviewList(Request $request)
    {

        // $reviews = Review::with('book','user')->orderBy('created_at', 'DESC')->paginate(8);

        // foreach($reviews as $review){
        //     $review->short_description = Str::limit($review->review, 30, '...');
        // }

        // return view('account.reviews.list',[
        //     'reviews'=> $reviews
        // ]);

        $reviews = Review::with('book', 'user')->orderBy('created_at', 'DESC');
        if (!empty($request->keyword)) {
            $reviews->where('review', 'like', '%' . $request->keyword . '%');
        }

        $reviews = $reviews->paginate(8);

        foreach ($reviews as $review) {
            $review->short_description = Str::limit($review->review, 30, '...');
        }

        return view('account.reviews.list', [
            'reviews' => $reviews
        ]);
    }

    public function edit($id)
    {
        $review =  Review::findOrFail($id);

        return view('account.reviews.edit', [
            'review' => $review
        ]);
    }

    public function updateReview($id, Request $request)
    {

        // retrive the review by its ID
        $review = Review::findOrFail($id);

        // validate the request input
        $validator = Validator::make($request->all(), [
            'review' => 'required',
            'status' => 'required'
        ]);

        // Redirect back with errors if validation fails
        if ($validator->fails()) {
            return redirect()->route('review.edit', $id)->withInput()->withErrors($validator);
        }

        // Update the review's fields

        $review->review = $request->review;
        $review->status = $request->status;
        $review->save();

        session()->flash('success', 'Review updated successfylly');

        return redirect()->route('review.list');
    }


    // Delete riview from db

    public function deleteReview(Request $request)
    {
        $id = $request->id;

        $review = Review::find($id);

        if ($review == null) {
            session()->flash('error', 'Review not found');
            return response()->json([
                'status' => false
            ]);
        } else {

            $review->delete();
            session()->flash('success', 'Review deleted successfylly');
            return response()->json([
                'status' => true
            ]);
        }
    }
}
