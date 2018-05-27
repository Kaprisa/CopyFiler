<?php

namespace App\Http\Composers;


use Illuminate\Contracts\View\View;

class SecureComposer
{

    public function compose(View $view)
    {
        $schema = parse_url(config('app.url'), PHP_URL_SCHEME);
        $secure = $schema == 'https' ? true : false;
        $view->with('secure', $secure);
    }
}