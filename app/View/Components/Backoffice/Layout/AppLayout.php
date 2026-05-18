<?php

namespace App\View\Components\Backoffice\Layout;

use Illuminate\View\Component;

class AppLayout extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $title;

    public function __construct($title = null)
    {
        $this->title = $title ?? config('app.name');
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.backoffice.layout.app-layout');
    }
}
