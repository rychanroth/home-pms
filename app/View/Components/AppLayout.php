<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public string $layout;

    /**
     * Create a new component instance.
     * We set the default to 'layouts.app' so your other pages don't break!
     */
    public function __construct(string $layout = 'layouts.app')
    {
        $this->layout = $layout;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // ✨ Now it dynamically loads whatever layout you pass it!
        return view($this->layout);
    }
}
