<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Http\Requests\BulkReadMessage;
use App\Http\Requests\SearchMessages;

use App\Http\Resources\ContactMessageResource;

use App\Services\ContactMessageService;

class ContactController extends Controller
{
    protected $messageService;
    protected $messagesCount;

    public function __construct()
    {
        $this->messageService = new ContactMessageService;
        $this->messageService->read = false;
        $this->messageService->count = true;
        $this->messagesCount = $this->messageService->messages();
        $this->messageService->count = null;
        $this->messageService->read = null;
    }

    public function messages(Request $request)
    {
        $this->messageService->perPage = $request->get('per_page', 10);
        $page = $request->query("page");
        if($page) $this->messageService->page = $page;
        

        $messages = $this->messageService->messages();

        return Inertia::render('Admin/ContactMessages', [
            'messages' => ContactMessageResource::collection($messages)->response()->getData(true),
            'messagesCount' => $this->messagesCount
        ]);
    }

    public function readMessage($messageId)
    {
        $message = $this->messageService->getMessage($messageId);
        if(!$message) return back()->withErrors(["error" => "This Message does not exist"]);

        $message = $this->messageService->markRead($message);

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read',
        ]);
    }

    public function bulkReadMessages(BulkReadMessage $request)
    {
        $this->messageService->bulkMarkRead($request->validated("messageIds"));

        return redirect()->back()->with('success', 'Messages marked as read successfully.');
    }

    public function delete($messageId)
    {
        $message = $this->messageService->getMessage($messageId);
        if(!$message) return back()->withErrors(["error" => "This Message does not exist"]);

        $this->messageService->delete($message);

        return response()->json([
            'success' => true,
            'message' => 'Message deleted',
        ]);
    }

    /**
     * Bulk delete messages.
     */
    public function bulkDelete(BulkReadMessage $request)
    {

        $this->messageService->bulkDelete($request->validated("messageIds"));

        return redirect()->back()->with('success', 'Messages deleted successfully.');
    }

    /**
     * Search contact messages.
     */
    public function search(SearchMessages $request)
    {

        $query = $request->get('query');
        $perPage = $request->get('per_page', 15);

        $messages = $this->messageService->search($query, $perPage);

        return Inertia::render('Admin/ContactMessages', [
            'messages' => ContactMessageResource::collection($messages)->response()->getData(true),
            'search' => $query,
            'messagesCount' => $this->messagesCount
        ]);
    }

    /**
     * Export contact messages to CSV.
     */
    public function export(Request $request)
    {
        $messages = ContactMessage::query()
            ->when($request->has('unread_only'), function ($query) {
                return $query->where('read', false);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'contact_messages_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($messages) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Message', 'Read', 'Created At', 'Read At']);
            
            // Add data rows
            foreach ($messages as $message) {
                fputcsv($file, [
                    $message->id,
                    $message->name,
                    $message->email,
                    $message->message,
                    $message->read ? 'Yes' : 'No',
                    $message->created_at->format('Y-m-d H:i:s'),
                    $message->read_at ? $message->read_at->format('Y-m-d H:i:s') : '',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
