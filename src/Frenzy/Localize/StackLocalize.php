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
		// Set the default locale.
		$default = $this->defaultLocale ?: $request->getPreferredLanguage($this->locales);
		$request->setDefaultLocale($default);

		$locale = $request->segment(1);

		if (in_array($locale, $this->locales))
		{
			$pathinfo = '/'.ltrim(substr($request->getPathInfo(), strlen($locale) +1), '/');

			if ($locale === $default) return RedirectResponse::create($pathinfo);

			$request->setLocale($locale);
			$request->setPathInfo($pathinfo);
		}

		return $this->app->handle($request, $type, $catch);
	}

}