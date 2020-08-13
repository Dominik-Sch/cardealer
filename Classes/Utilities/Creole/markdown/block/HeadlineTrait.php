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

namespace cebe\markdown\block;

/**
 * Adds the headline blocks
 */
trait HeadlineTrait
{
	/**
	 * identify a line as a headline
	 */
	protected function identifyHeadline($line, $lines, $current)
	{
		return (
			// heading with #
			$line[0] === '#' && !preg_match('/^#\d+/', $line)
			||
			// underlined headline
			!empty($lines[$current + 1]) &&
			(($l = $lines[$current + 1][0]) === '=' || $l === '-') &&
			preg_match('/^(\-+|=+)\s*$/', $lines[$current + 1])
		);
	}

	/**
	 * Consume lines for a headline
	 */
	protected function consumeHeadline($lines, $current)
	{
		if ($lines[$current][0] === '#') {
			// ATX headline
			$level = 1;
			while (isset($lines[$current][$level]) && $lines[$current][$level] === '#' && $level < 6) {
				$level++;
			}
			$block = [
				'headline',
				'content' => $this->parseInline(trim($lines[$current], "# \t")),
				'level' => $level,
			];
			return [$block, $current];
		} else {
			// underlined headline
			$block = [
				'headline',
				'content' => $this->parseInline($lines[$current]),
				'level' => $lines[$current + 1][0] === '=' ? 1 : 2,
			];
			return [$block, $current + 1];
		}
	}

	/**
	 * Renders a headline
	 */
	protected function renderHeadline($block)
	{
		$tag = 'h' . $block['level'];
		return "<$tag>" . $this->renderAbsy($block['content']) . "</$tag>\n";
	}

	abstract protected function parseInline($text);
	abstract protected function renderAbsy($absy);
}
