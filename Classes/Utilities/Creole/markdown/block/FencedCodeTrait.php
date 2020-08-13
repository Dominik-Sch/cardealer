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
 * Adds the fenced code blocks
 *
 * automatically included 4 space indented code blocks
 */
trait FencedCodeTrait
{
	use CodeTrait;

	/**
	 * identify a line as the beginning of a fenced code block.
	 */
	protected function identifyFencedCode($line)
	{
		return ($l = $line[0]) === '`' && strncmp($line, '```', 3) === 0 ||
				$l === '~' && strncmp($line, '~~~', 3) === 0;
	}

	/**
	 * Consume lines for a fenced code block
	 */
	protected function consumeFencedCode($lines, $current)
	{
		// consume until ```
		$line = rtrim($lines[$current]);
		$fence = substr($line, 0, $pos = strrpos($line, $line[0]) + 1);
		$language = substr($line, $pos);
		$content = [];
		for ($i = $current + 1, $count = count($lines); $i < $count; $i++) {
			if (rtrim($line = $lines[$i]) !== $fence) {
				$content[] = $line;
			} else {
				break;
			}
		}
		$block = [
			'code',
			'content' => implode("\n", $content),
		];
		if (!empty($language)) {
			$block['language'] = $language;
		}
		return [$block, $i];
	}
}
