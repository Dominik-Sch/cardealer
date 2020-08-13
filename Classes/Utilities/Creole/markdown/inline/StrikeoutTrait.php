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
 * Adds strikeout inline elements
 */
trait StrikeoutTrait
{
	/**
	 * Parses the strikethrough feature.
	 * @marker ~~
	 */
	protected function parseStrike($markdown)
	{
		if (preg_match('/^~~(.+?)~~/', $markdown, $matches)) {
			return [
				[
					'strike',
					$this->parseInline($matches[1])
				],
				strlen($matches[0])
			];
		}
		return [['text', $markdown[0] . $markdown[1]], 2];
	}

	protected function renderStrike($block)
	{
		return '<del>' . $this->renderAbsy($block[1]) . '</del>';
	}
}
