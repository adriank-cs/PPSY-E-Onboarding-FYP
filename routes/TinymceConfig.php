<?php

namespace App\View\Components\Head;

use Illuminate\View\Component;

class TinymceConfig extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.head.tinymce-config');
    }
}
