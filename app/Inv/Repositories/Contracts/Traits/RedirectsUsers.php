<?php

namespace App\Inv\Repositories\Contracts\Traits;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
           // return $this->redirectTo();

        }
        return redirect()->route('backend_login_open');

        // return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
