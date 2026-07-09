<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Submit a provider reply to a customer review.
     */
    public function reply(Request $request, Review $review)
    {
        // Guard check: only the provider who received the review can reply
        if ($review->provider_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'reply' => 'required|string|min:3|max:1000',
        ], [
            'reply.required' => 'Reply content cannot be empty.',
            'reply.min' => 'Reply must be at least 3 characters.',
        ]);

        $review->update([
            'reply' => $request->reply,
        ]);

        return back()->with('success', 'Your reply to the review has been published.');
    }
}
