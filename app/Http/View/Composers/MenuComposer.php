<?php

namespace App\Http\View\Composers;

use App\Repositories\UserRepository;
use App\Services\MenuService;
use Carbon\Carbon;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $menu = (new MenuService())->getMenu();

        $view->with('admin_menu', $menu['adminMenu']);
        $view->with('user_menu', $menu['userMenu']);
    }
}
