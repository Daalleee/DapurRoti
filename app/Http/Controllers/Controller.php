<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    /**
     * Store the intended URL in the session
     */
    protected function storeIntendedUrl(Request $request): void
    {
        if ($request->has('next') && $this->isValidNextUrl($request->next)) {
            session(['url.intended' => $request->next]);
        } elseif ($request->headers->get('referer')) {
            $referer = $request->headers->get('referer');
            if ($this->isValidNextUrl($referer)) {
                session(['url.intended' => $referer]);
            }
        }
    }
    
    /**
     * Validate if the next URL is safe to redirect to
     */
    private function isValidNextUrl(string $url): bool
    {
        // Check if it's a relative URL or if the domain matches the app's domain
        return str_starts_with($url, '/') || 
               str_starts_with($url, request()->getSchemeAndHttpHost());
    }
}
