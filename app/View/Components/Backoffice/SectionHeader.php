<?php

namespace App\View\Components\Backoffice;

use Illuminate\View\Component;

class SectionHeader extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $heading, $breadcrumb, $icon, $search;

    public function __construct($heading, $breadcrumb, $icon = null, $search = false)
    {
        $this->heading = $heading;
        $this->breadcrumb = $breadcrumb;
        $this->icon = $icon ?? 'fas fa-fire';
        $this->search = $search;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.backoffice.section-header');
    }
}
