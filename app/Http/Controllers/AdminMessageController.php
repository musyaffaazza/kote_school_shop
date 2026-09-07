<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMessageController extends Controller
{
    /**
     * Tampilkan halaman daftar pesan masuk
     */
    public function index(Request $request): View
    {
        $query = ContactMessage::query()->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subjek', 'like', "%{$search}%")
                    ->orWhere('pesan', 'like', "%{$search}%");
            });
        }

        // Subject filter
        if ($request->filled('filter') && $request->input('filter') !== 'all') {
            $query->where('subjek', $request->input('filter'));
        }

        $messages = $query->get();

        // Get active message
        $activeMessage = null;
        if ($messages->isNotEmpty()) {
            $activeId = $request->input('active_id');
            if ($activeId) {
                $activeMessage = $messages->firstWhere('id', $activeId);
            }
            if (! $activeMessage) {
                $activeMessage = $messages->first();
            }

            // Mark as read if not already
            if (! $activeMessage->is_read) {
                $activeMessage->update(['is_read' => true]);
            }
        }

        return view('admin.messages.index', [
            'messages' => $messages,
            'activeMessage' => $activeMessage,
            'filter' => $request->input('filter', 'all'),
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Hapus pesan masuk
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
