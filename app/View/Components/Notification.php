<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Notification as DataNotification;

class Notification extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $notifications = DataNotification::where('receiver_role_id', auth()->user()->role_id)->where('receiver_user_id', auth()->user()->id)->where('read_status', 0)->latest()->get();

        return view('components.notification', compact('notifications'));
    }
}
