<?php

namespace App\Controllers;

/**
 * Language Controller
 * 
 * Handles language switching.
 */
class LanguageController extends BaseController
{
    /**
     * Switch language
     */
    public function switch(string $locale)
    {
        $supportedLocales = ['en', 'zh', 'th'];
        
        if (in_array($locale, $supportedLocales)) {
            $this->session->set('locale', $locale);
        }

        return redirect()->back();
    }
}

