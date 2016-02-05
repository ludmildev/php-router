<?php
namespace Lib;

class Template
{
	private $_baseUrl = '';
	private $_template = '';
	private $_content;
	private $_folder;

	private $_includeHeader;
	private $_includeFooter;

	public function __construct($viewsPath = '', $template = 'default', $header = true, $footer = true)
	{
		$this->_baseUrl = $viewsPath;
		$this->_template = $template;
		$this->_includeHeader = $header;
		$this->_includeFooter = $footer;

		return $this;
	}

	public function load($file)
	{
		$_file = $this->_baseUrl . DIRECTORY_SEPARATOR . $this->_template . DIRECTORY_SEPARATOR . $this->getFolder() . DIRECTORY_SEPARATOR . $file . '.php';

		if (is_file($_file) && is_readable($_file))
			$this->_content[$file] = $_file;

		return $this;
	}

	public function setFolder($folder = '') {
		$this->_folder = $folder;

		return $this;
	}
	private function getFolder() {
		return $this->_folder;
	}

    private function handleEcho($pageContent, $params)
    {
        $matches = '';

        preg_match_all('/{{echo:(.*)}}/i', $pageContent, $matches, PREG_SET_ORDER);

        foreach($matches as $match)
        {
            if (isset($params[$match[1]])) {
                $pageContent = preg_replace('/{{echo:'.$match[1].'}}/', $params[$match[1]], $pageContent);
            }
        }

        return $pageContent;
    }

    private function handleInclude($pageContent, $params)
    {
        $matches = '';

        preg_match_all('/{{include:(.*)}}/i', $pageContent, $matches, PREG_SET_ORDER);

        foreach($matches as $match)
        {
            $includePath = str_replace('.', DIRECTORY_SEPARATOR, $match[1]);

            $file = $this->_baseUrl . DIRECTORY_SEPARATOR . $includePath . '.php';

            if (is_file($file) && is_readable($file))
            {
                $subInclude = $this->handleEcho(file_get_contents($this->_baseUrl . DIRECTORY_SEPARATOR . $includePath . '.php'), $params);

                $pageContent = preg_replace('/{{include:'.$match[1].'}}/', $subInclude, $pageContent);
            }
        }

        return $pageContent;
    }

	public function render($params = [])
	{
		$render = '';

		if ($this->_includeHeader)
			$render .= $this->_includeHeader ? file_get_contents($this->_baseUrl . DIRECTORY_SEPARATOR . $this->_template . DIRECTORY_SEPARATOR . 'header.php') : '';

		foreach($this->_content as $page)
		{
            $content = $this->handleEcho(file_get_contents($page), $params);
            $content = $this->handleInclude($content, $params);

			$render .= $content;
		}

		if ($this->_includeFooter)
			$render .= $this->_includeFooter ? file_get_contents($this->_baseUrl . DIRECTORY_SEPARATOR . $this->_template . DIRECTORY_SEPARATOR . 'footer.php') : '';

        eval('?>' . $render);
	}
}