<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishController extends Controller
{
    public function index()
    {
        // Fetch all wishes from the database
        $wishes = \App\Models\Wish::orderBy('created_at', 'desc')->get();

        // Return the view with the wishes data
        return view('wish', compact('wishes'));
    }

    public function store(Request $request)
    {
        // Check honeypot field to protect against spam
        if ($request->has('honeypot') && !empty($request->honeypot)) {
            return response()->json(['error' => 'Phát hiện spam!'], 400);
        }

        // Validate the request data with custom messages in Vietnamese
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'wish_message' => 'required|string|max:255|min:10',
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'name.string' => 'Tên phải là một chuỗi ký tự.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',
            'wish_message.required' => 'Lời chúc là bắt buộc.',
            'wish_message.string' => 'Lời chúc phải là một chuỗi ký tự.',
            'wish_message.max' => 'Lời chúc không được vượt quá 255 ký tự.',
            'wish_message.min' => 'Lời chúc phải có ít nhất 10 ký tự.',
        ]);

        // Create a new wish
        $wish = \App\Models\Wish::create([
            'name' => $validatedData['name'],
            'wish_message' => $validatedData['wish_message'],
            'wish_status' => 0, // Default status
        ]);

        // Return success response
        return response()->json(['success' => 'Cám ơn bạn đã gửi lời chúc đến chúng tôi!', 'wish' => $wish], 201);
    }

    public function destroy($id)
    {
        // Find the wish by ID and delete it
        $wish = \App\Models\Wish::findOrFail($id);
        $wish->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Lời chúc đã được xóa thành công!');
    }

    public function updateStatus($id)
    {
        // Find the wish by ID
        $wish = \App\Models\Wish::findOrFail($id);

        // Toggle the status
        $wish->wish_status = !$wish->wish_status;
        $wish->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Trạng thái lời chúc đã được cập nhật!');
    }

    public function updateAllStatuses(Request $request)
    {
        $status = $request->status;
        // Update the status of all wishes to 1
        \App\Models\Wish::query()->update(['wish_status' => $status]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Tất cả trạng thái lời chúc đã được cập nhật!');
    }

    public function getLatestWishes()
    {
        // Fetch the latest 10 wishes with status 1 from the database
        $latestWishes = \App\Models\Wish::where('wish_status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray();

        // Return the wishes as a JSON response
        return response()->json($latestWishes);
    }
}
