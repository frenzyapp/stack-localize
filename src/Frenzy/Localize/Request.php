<?php namespace Frenzy\Localize;

class Request extends \Illuminate\Http\Request {

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
		$root = rtrim($this->getSchemeAndHttpHost().$this->getBaseUrl(), '/');

		if ( ! $trueRoot and $this->defaultLocale !== $this->locale)
		{
			$root .= '/'.$this->locale;
		}

		return $root;
	}
}