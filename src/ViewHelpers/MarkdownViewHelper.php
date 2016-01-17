<?php
namespace TYPO3Fluid\Docs\ViewHelpers;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * @api
 */
class MarkdownViewHelper extends AbstractViewHelper {
	/**
	 * @var boolean
	 */
	protected $escapeChildren = FALSE;

	/**
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;

	/**
	 * Comments out the tag content
	 *
	 * @return string
	 * @api
	 */
	public function render(){
		$parser = new \Parsedown();
		return $parser->text($this->renderChildren());
	}
}
