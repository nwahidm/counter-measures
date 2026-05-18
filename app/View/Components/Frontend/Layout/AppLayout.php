<?php

namespace App\View\Components\Frontend\Layout;

use Illuminate\View\Component;

class AppLayout extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $title;
    public $image;

    public function __construct($title = null, $image = '')
    {
        $this->title = $title;
        $this->image = $image;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.frontend.layout.app-layout');
    }
}
