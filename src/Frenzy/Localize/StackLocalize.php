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
		$locale = isset($locale[0]) ? $locale[0] : null;

		if (in_array($locale, $this->locales))
		{
			$pathinfo = '/'.ltrim(substr($request->getPathInfo(), strlen($locale) +1), '/');

			if ($locale === $default) return RedirectResponse::create($pathinfo);

			$request->server->set('REQUEST_URI', $pathinfo);
			$request->setDefaultLocale($default);
			$request->setLocale($locale);
			
			$request = $request->duplicate();
		}

		return $this->app->handle($request, $type, $catch);
	}

}