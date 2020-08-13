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

namespace EXOTEC\Cardealer\Utilities\Creole\block;

/**
 * Adds the headline blocks
 */
trait HeadlineTrait
{
	/**
	 * identify a line as a headline
	 * A headline always starts with a '=', with leading white spaces permitted.
	 */
	protected function identifyHeadline($line)
	{
		$line = ltrim($line);
		return (
			// heading with =
			isset($line[0]) && $line[0] === '='
		);
	}

	/**
	 * Consume lines for a headline
	 */
	protected function consumeHeadline($lines, $current)
	{
		$line = trim($lines[$current]);
		$level = 1;
		while (isset($line[$level]) && $line[$level] === '=' && $level < 6) {
			$level++;
		}
		$block = [
			'headline',
			// parse headline content. The leading and trailing '='s are removed.
			'content' => $this->parseInline(trim($line, " \t=")),
			'level' => $level,
		];
		return [$block, $current];
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
