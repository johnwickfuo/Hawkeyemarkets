<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\ClientReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ReviewController extends Controller
{
    public function index()
    {
        $page_title = __('Client Reviews');
        $template = config('site.template');
        $reviews = ClientReview::all();
        return view('templates.' . $template . '.blades.admin.settings.reviews', compact('page_title', 'reviews', 'template'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'role', 'review', 'rating']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/images/team'), $imageName);
            $data['image'] = $imageName;
        }

        ClientReview::create($data);

        return response()->json(['message' => __('Review created successfully')]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $review = ClientReview::findOrFail($id);
        $data = $request->only(['name', 'role', 'review', 'rating']);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($review->image && File::exists(public_path('assets/images/team/' . $review->image))) {
                File::delete(public_path('assets/images/team/' . $review->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/images/team'), $imageName);
            $data['image'] = $imageName;
        }

        $review->update($data);

        return response()->json(['message' => __('Review updated successfully')]);
    }

    public function delete($id)
    {
        $review = ClientReview::findOrFail($id);

        if ($review->image && File::exists(public_path('assets/images/team/' . $review->image))) {
            File::delete(public_path('assets/images/team/' . $review->image));
        }

        $review->delete();

        return response()->json(['message' => __('Review deleted successfully')]);
    }
}
