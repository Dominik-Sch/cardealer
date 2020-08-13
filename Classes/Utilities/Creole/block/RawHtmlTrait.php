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
 * Adds the raw html blocks
 */
trait RawHtmlTrait
{
	/**
	 * @var bool whether to support raw html blocks.
	 * Defaults to `false`.
	 */
	public $useRawHtml = false;

    /**
     * @var callable output filter
     * Defaults to null.
     */
	public $rawHtmlFilter = null;

	/**
	 * identify a line as the beginning of a raw html block.
	 */
	protected function identifyRawHtml($line)
	{
		return $this->useRawHtml && (strcmp(rtrim($line), '<<<') === 0);
	}

	/**
	 * Consume lines for a raw html block
	 */
	protected function consumeRawHtml($lines, $current)
	{
		// consume until >>>
		$content = [];
		for ($i = $current + 1, $count = count($lines); $i < $count; $i++) {
			$line = rtrim($lines[$i]);
			if (strcmp($line, '>>>') !== 0) {
				$content[] = $line;
			} else {
				break;
			}
		}
		$block = [
			'rawHtml',
			'content' => implode("\n", $content),
		];
		return [$block, $i];
	}

	/**
	 * Renders a raw html block
	 */
	protected function renderRawHtml($block)
	{
        $output = $block['content'];
        if (is_callable($this->rawHtmlFilter, true)) {
            $output = call_user_func($this->rawHtmlFilter, $output);
        }
		return $output . "\n";
	}
}
