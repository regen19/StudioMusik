<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() 
    {
        $notifs = auth()->user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifs'));
    }

    public function markRead($id)
    {
        $n = auth()->user()->notifications()->where('id',$id)->firstOrFail();
        $n->markAsRead();
        return back()->with('ok','Notifikasi ditandai sudah dibaca.');
    }

    public function readAll() 
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}