<?php
namespace TYPO3Fluid\Docs\TemplateProcessor;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use TYPO3Fluid\Fluid\Core\Parser\Patterns;
use TYPO3Fluid\Fluid\Core\Parser\TemplateParser;
use TYPO3Fluid\Fluid\Core\Parser\TemplateProcessorInterface;
use TYPO3Fluid\Fluid\Core\Parser\UnknownNamespaceException;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 *
 */
class CodeTemplateProcessor implements TemplateProcessorInterface {
	/**
	 * @var RenderingContextInterface
	 */
	protected $renderingContext;

	/**
	 * @var array()
	 */
	protected $localNamespaces = array();

	/**
	 * @param RenderingContextInterface $renderingContext
	 * @return void
	 */
	public function setRenderingContext(RenderingContextInterface $renderingContext) {
		$this->renderingContext = $renderingContext;
	}

	/**
	 * Pre-process the template source before it is
	 * returned to the TemplateParser or passed to
	 * the next TemplateProcessorInterface instance.
	 *
	 * @param string $templateSource
	 * @return string
	 */
	public function preProcessSource($templateSource) {
		preg_match_all('/<d:code>(.*?)<\/d:code>/s', $templateSource, $matches);
		foreach($matches[1] as $key => $match) {
			$match = str_replace('{', '###BRACKET_OPEN###', $match);
			$match = str_replace('}', '###BRACKET_CLOSE###', $match);
			$templateSource = str_replace($matches[1][$key], htmlspecialchars($match), $templateSource);
		}
		return $templateSource;
	}
}
