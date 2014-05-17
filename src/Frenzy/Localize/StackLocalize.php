<?php namespace Frenzy\Localize;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StackLocalize implements HttpKernelInterface {

    /**
     * An array of valid locales.
     *
     * @var array
     */
    protected $locales;

    /**
     * The default locale.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * Constructor.
     *
     * @param HttpKernelInterface $app
     * @param array               $locales
     * @param string              $defaultLocale
     */
    public function __construct(HttpKernelInterface $app, array $locales = null, $defaultLocale)
    {
        $this->app = $app;
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        // Check for the default locale.
        $default = $this->defaultLocale ?: $request->getPreferredLanguage($this->locales);

        // URI prefix lookup.
        $locale = explode('/', trim($request->getPathInfo(), '/'));
        $locale = array_filter($locale, function($v) { return $v != ''; });
        $locale = isset($locale[0]) ? $locale[0] : $default;

        $isValidLocale = in_array($locale, $this->locales);
        if ( ! $isValidLocale) $locale = $default;

        $pathInfo = $request->getPathInfo();

        // If the locale in the URI is the default, redirect to URI without locale.
        if ($isValidLocale and $locale === $default and $pathInfo !== '/')
        {
            $redirect = '/'.ltrim(substr($pathInfo, strlen($locale) +1), '/');

            if ($request->getQueryString()) $redirect .= '?'.$request->getQueryString();

            return RedirectResponse::create($redirect, 301, $request->headers->all());
        }

        // Get the root path of the request.
        $root = $request->server->get('SCRIPT_FILENAME');

        // Duplicate the request so we can change the root path and detected locales.
        $newRequest = $request->duplicate();
        $newRequest->server->set('SCRIPT_FILENAME', $root . '/' . $locale);
        $newRequest->setDefaultLocale($default);
        $newRequest->setLocale($locale);

        return $this->app->handle($newRequest, $type, $catch);
    }

}
