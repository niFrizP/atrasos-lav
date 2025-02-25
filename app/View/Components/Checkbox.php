<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Checkbox extends Component
{
    public $checked;

    public function __construct($checked = null)
    {
        $this->checked = $checked;
    }

    public function render()
    {
        return view('components.checkbox');
    }
}
