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

namespace cebe\markdown\tests;
use cebe\markdown\MarkdownExtra;

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @group extra
 */
class MarkdownExtraTest extends BaseMarkdownTest
{
	public function createMarkdown()
	{
		return new MarkdownExtra();
	}

	public function getDataPaths()
	{
		return [
			'markdown-data' => __DIR__ . '/markdown-data',
			'extra-data' => __DIR__ . '/extra-data',
		];
	}
}