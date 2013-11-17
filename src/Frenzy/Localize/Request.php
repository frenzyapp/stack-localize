<?php namespace Frenzy\Localize;

class Request extends \Symfony\Component\HttpFoundation\Request {

	/**
	 * Change the path info.
	 * 
	 * @param  string $pathInfo
	 * @return string
	 */
	public function setPathInfo($pathInfo)
	{
		$this->pathInfo = $pathInfo;
	}

	/**
	 * Get the root URL for the application.
	 *
	 * @param  bool   $trueRoot
	 * @return string
	 */
	public function root($trueRoot = false)
	{
		$root = $this->getSchemeAndHttpHost().$this->getBaseUrl();

		if ( ! $trueRoot)
		{
			$root .= $this->getLocale();
		}

		return rtrim($root, '/');
	}
}