<?php

namespace App\Lib;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function __;
use function redirect;

class RedirectUtils
{

    public static function redirectBack(Request $request, string $defaultRoute):RedirectResponse {

        if ($request->query->has('back')) {
            $backUrl = $request->query->get('back');
            $response = redirect()->to($backUrl);
        } else {
            $response = redirect()->route($defaultRoute);
        }

        return $response->with('success', __('messages.edit_success'));
    }
}
