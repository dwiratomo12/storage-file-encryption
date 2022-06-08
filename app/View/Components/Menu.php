<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Menu extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $active;

    public function __construct($active)
    {
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.menu', [
            'active' => $this->active
        ]);
    }

    public function list()
    {
        return [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon'  => 'fas fa-tachometer-alt'
            ],
            // [
            //     'label' => 'Files',
            //     'route' => 'dashboard.files',
            //     'icon'  => 'fas fa-file'
            // ],
            // [
            //     'label' => 'Movies',
            //     'route' => 'dashboard.movies',
            //     'icon'  => 'fas fa-university'
            // ],
            // [
            //     'label' => 'Tickets',
            //     'route' => 'dashboard.tickets',
            //     'icon'  => 'fas fa-ticket-alt'
            // ],
            [
                'label' => 'Users',
                'route' => 'dashboard.clients',
                'icon'  => 'fas fa-users'
            ],
        ];
    }

    public function client()
    {
        return [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'icon'  => 'fas fa-tachometer-alt'
            ],
            [
                'label' => 'Files',
                'route' => 'dashboard.files',
                'icon'  => 'fas fa-file'
            ],
        ];
    }
    
    public function isActive($label)
    {
        return $label === $this->active;
    }
}