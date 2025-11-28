<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Language Controller
 * Handles language switching
 */
class LanguageController extends Controller
{
    /**
     * Supported languages
     */
    protected array $supportedLocales = ['en', 'th', 'zh'];

    /**
     * Switch language
     */
    public function switch(string $locale = 'en')
    {
        // Validate locale
        if (!in_array($locale, $this->supportedLocales)) {
            $locale = 'en';
        }

        // Store in session
        session()->set('locale', $locale);

        // Also set a cookie for persistence
        $response = service('response');
        $response->setCookie([
            'name'   => 'locale',
            'value'  => $locale,
            'expire' => 60 * 60 * 24 * 365, // 1 year
            'path'   => '/',
        ]);

        // Redirect back to previous page
        $referer = $this->request->getHeaderLine('Referer');
        if ($referer && strpos($referer, base_url()) === 0) {
            return redirect()->to($referer);
        }

        return redirect()->to('/');
    }

    /**
     * Get current language
     */
    public function current(): string
    {
        // Check session first
        $locale = session()->get('locale');
        
        // Then check cookie
        if (!$locale) {
            $locale = $this->request->getCookie('locale');
        }
        
        // Fallback to default
        if (!$locale || !in_array($locale, $this->supportedLocales)) {
            $locale = config('App')->defaultLocale;
        }

        return $locale;
    }
}
