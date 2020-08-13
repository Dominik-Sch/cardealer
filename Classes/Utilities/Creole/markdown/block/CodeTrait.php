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
 * Adds the 4 space indented code blocks
 */
trait CodeTrait
{
	/**
	 * identify a line as the beginning of a code block.
	 */
	protected function identifyCode($line)
	{
		// indentation >= 4 or one tab is code
		return ($l = $line[0]) === ' ' && $line[1] === ' ' && $line[2] === ' ' && $line[3] === ' ' || $l === "\t";
	}

	/**
	 * Consume lines for a code block element
	 */
	protected function consumeCode($lines, $current)
	{
		// consume until newline

		$content = [];
		for ($i = $current, $count = count($lines); $i < $count; $i++) {
			$line = $lines[$i];

			// a line is considered to belong to this code block as long as it is intended by 4 spaces or a tab
			if (isset($line[0]) && ($line[0] === "\t" || strncmp($line, '    ', 4) === 0)) {
				$line = $line[0] === "\t" ? substr($line, 1) : substr($line, 4);
				$content[] = $line;
			// but also if it is empty and the next line is intended by 4 spaces or a tab
			} elseif (($line === '' || rtrim($line) === '') && isset($lines[$i + 1][0]) &&
				      ($lines[$i + 1][0] === "\t" || strncmp($lines[$i + 1], '    ', 4) === 0)) {
				if ($line !== '') {
					$line = $line[0] === "\t" ? substr($line, 1) : substr($line, 4);
				}
				$content[] = $line;
			} else {
				break;
			}
		}

		$block = [
			'code',
			'content' => implode("\n", $content),
		];
		return [$block, --$i];
	}

	/**
	 * Renders a code block
	 */
	protected function renderCode($block)
	{
		$class = isset($block['language']) ? ' class="language-' . $block['language'] . '"' : '';
		return "<pre><code$class>" . htmlspecialchars($block['content'] . "\n", ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</code></pre>\n";
	}
}
