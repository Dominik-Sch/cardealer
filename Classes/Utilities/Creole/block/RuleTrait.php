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
 * Adds horizontal rules
 */
trait RuleTrait
{
	/**
	 * identify a line as a horizontal rule.
	 * The exact string of '----', with possible white spaces before and/or after it
	 */
	protected function identifyHr($line)
	{
		return preg_match('/^\s*----\s*$/', $line);
	}

	/**
	 * Consume a horizontal rule
	 */
	protected function consumeHr($lines, $current)
	{
		return [['hr'], $current];
	}

	/**
	 * Renders a horizontal rule
	 */
	protected function renderHr($block)
	{
		return $this->html5 ? "<hr>\n" : "<hr />\n";
	}

} 