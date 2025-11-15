<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.feedback.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        // Tandai sebagai sudah dibaca
        if (!$contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }
        return view('admin.feedback.show', ['message' => $contactMessage]);
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return redirect()->route('admin.feedback.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
