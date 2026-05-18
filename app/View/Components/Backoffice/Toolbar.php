<?php

namespace App\View\Components\Backoffice;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toolbar extends Component
{
    /**
     * Create a new component instance.
     */
    public $breadcrumb, $heading, $subheading;
    public function __construct($breadcrumb, $heading = '', $subheading = '')
    {
        $this->breadcrumb = $breadcrumb;
        $this->heading = $heading;
        $this->subheading = $subheading;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backoffice.toolbar');
    }
}
