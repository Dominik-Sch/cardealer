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

namespace cebe\markdown\inline;

/**
 * Adds inline emphasizes and strong elements
 */
trait EmphStrongTrait
{
	/**
	 * Parses empathized and strong elements.
	 * @marker _
	 * @marker *
	 */
	protected function parseEmphStrong($text)
	{
		$marker = $text[0];

		if (!isset($text[1])) {
			return [['text', $text[0]], 1];
		}

		if ($marker == $text[1]) { // strong
			// work around a PHP bug that crashes with a segfault on too much regex backtrack
			// check whether the end marker exists in the text
			// https://github.com/erusev/parsedown/issues/443
			// https://bugs.php.net/bug.php?id=45735
			if (strpos($text, $marker . $marker, 2) === false) {
				return [['text', $text[0] . $text[1]], 2];
			}

			if ($marker == '*' && preg_match('/^[*]{2}((?:[^*]|[*][^*]*[*])+?)[*]{2}(?![*]{2})/s', $text, $matches) ||
				$marker == '_' && preg_match('/^__((?:[^_]|_[^_]*_)+?)__(?!__)/us', $text, $matches)) {

				return [
					[
						'strong',
						$this->parseInline($matches[1]),
					],
					strlen($matches[0])
				];
			}
		} else { // emph
			// work around a PHP bug that crashes with a segfault on too much regex backtrack
			// check whether the end marker exists in the text
			// https://github.com/erusev/parsedown/issues/443
			// https://bugs.php.net/bug.php?id=45735
			if (strpos($text, $marker, 1) === false) {
				return [['text', $text[0]], 1];
			}

			if ($marker == '*' && preg_match('/^[*]((?:[^*]|[*][*][^*]+?[*][*])+?)[*](?![*][^*])/s', $text, $matches) ||
				$marker == '_' && preg_match('/^_((?:[^_]|__[^_]*__)+?)_(?!_[^_])\b/us', $text, $matches)) {
				return [
					[
						'emph',
						$this->parseInline($matches[1]),
					],
					strlen($matches[0])
				];
			}
		}
		return [['text', $text[0]], 1];
	}

	protected function renderStrong($block)
	{
		return '<strong>' . $this->renderAbsy($block[1]) . '</strong>';
	}

	protected function renderEmph($block)
	{
		return '<em>' . $this->renderAbsy($block[1]) . '</em>';
	}
}
