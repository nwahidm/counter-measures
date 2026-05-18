<?php

namespace App\View\Components\Frontend;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $breadcrumb, $companyId, $category, $slug;

    public function __construct($breadcrumb, $companyId, $category, $slug)
    {
        $this->breadcrumb = $breadcrumb;
        $this->companyId = $companyId;
        $this->category = $category;
        $this->slug = $slug;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.frontend.breadcrumb');
    }
}
