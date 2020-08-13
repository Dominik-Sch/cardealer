<?php
/**
 * Copyright (c) 2018.  Alexander Weber <weber@exotec.de> - exotec - TYPO3 Services
 *
 * All rights reserved
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 *
 */

namespace EXOTEC\Cardealer\Utilities\Creole\inline;

/**
 * Adds inline emphasizes and strong elements
 */
trait EmphStrongTrait
{
	/**
	 * Parses strong element: ** ... **
	 * @marker **
	 */
	protected function parseStrong($text)
	{
		$pattern =<<< REGEXP
/^\*\*(
  (.*?{{{.*?}}}.*?)+?|  # including inline code span
  (?!.*{{{.*?}}}).*?    # without inline code span
)\*\*/sx
REGEXP;
		if (preg_match($pattern, $text, $matches)) {
			return [
				[
					'strong',
					$this->parseInline($matches[1])
				],
				strlen($matches[0])
			];
		} else {
			// no ending ** ... should be treated as strong
			return [
				[
					'strong',
					$this->parseInline(substr($text,2))
				],
				strlen($text)
			];
		}
	}

	/**
	 * Parses strong element: // ... //
	 * @marker //
	 */
	protected function parseEmph($text)
	{
		// must take care of code elements, 'http://', 'https://', and 'ftp://'
		$pattern =<<< REGEXP
/^\/\/(
  (.*?{{{.*?}}}.*?)+?|  # including inline code span
  ((?!.*{{{.*?}}}).*?)  # without inline code span
)(?<!http:|(?<=h)ttps:|(?<=f)tp:)\/\/(?!\/)/sx
REGEXP;

		if (preg_match($pattern, $text, $matches)) {
			return [
				[
					'emph',
					$this->parseInline($matches[1])
				],
				strlen($matches[0])
			];
		} else {
			// no ending // ... should be treated as em
			return [
				[
					'emph',
					$this->parseInline(substr($text,2))
				],
				strlen($text)
			];
		}
	}

	protected function renderStrong($block)
	{
		return '<strong>' . $this->renderAbsy($block[1]) . '</strong>';
	}

	protected function renderEmph($block)
	{
		return '<em>' . $this->renderAbsy($block[1]) . '</em>';
	}

	abstract protected function parseInline($text);
	abstract protected function renderAbsy($absy);
}
