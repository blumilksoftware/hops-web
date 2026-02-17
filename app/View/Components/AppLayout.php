<?php

declare(strict_types=1);

namespace HopsWeb\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function render(): View
    {
        return view("layouts.app");
    }
}
