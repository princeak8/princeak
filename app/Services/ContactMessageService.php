<?php

namespace App\Services;

use App\Models\ContactMessage;

class ContactMessageService
{
    public $read = null;
    public $count = null;
    public $perPage = null;
    public $page = null;

    public function messages()
    {
        $query = ContactMessage::query();
        if($this->read !== null) $query = ContactMessage::where("read", $this->read);
        if($this->count) return $query->count();
        if($this->perPage) {
            ($this->page) ? $query->paginate($this->perPage, ['*'], 'page', $this->page) : $query->paginate($this->perPage);
        }
        return $query->orderBy("created_at", "DESC")->get();
        return $query->get();
    }

    public function getMessage($id)
    {
        return ContactMessage::find($id);
    }

    public function save($data)
    {
        $message = new ContactMessage;
        $message->name = $data['name'];
        $message->message = $data['message'];
        if(isset($data['email'])) $message->email = $data['email'];
        if(isset($data['userId'])) $message->user_id = $data['userId'];

        $message->save();

        return $message;
    }

    public function markRead($message)
    {
        $message->read = true;
        $message->update();

        return $message;
    }

    public function bulkMarkRead($messageIds)
    {
        ContactMessage::whereIn('id', $messageIds)
            ->update(['read' => true, 'read_at' => now()]);
    }

    public function search($searchText, $perPage)
    {
        return ContactMessage::query()
            ->where(function ($q) use ($searchText) {
                $q->where('name', 'like', "%{$searchText}%")
                  ->orWhere('email', 'like', "%{$searchText}%")
                  ->orWhere('message', 'like', "%{$searchText}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function delete($message)
    {
        $message->delete();
    }

    public function bulkDelete($messageIds)
    {
        ContactMessage::whereIn('id', $messageIds)->delete();
    }
}