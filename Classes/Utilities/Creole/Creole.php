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

namespace EXOTEC\Cardealer\Utilities\Creole;

/**
 * Creole wiki parser for [Creole 1.0 spec](http://www.wikicreole.org/wiki/Creole1.0).
 *
 * @author Nobuo Kihara <softark@gmail.com>
 */
class Creole extends \EXOTEC\Cardealer\Utilities\Creole\markdown\Parser
{
// include block element parsing using traits
	use block\CodeTrait;
	use block\HeadlineTrait;
	use block\ListTrait;
	use block\TableTrait;
	use block\RuleTrait;
	use block\RawHtmlTrait;

// include inline element parsing using traits
	use inline\CodeTrait;
	use inline\EmphStrongTrait;
	use inline\LinkTrait;

	/**
	 * @var boolean whether to format markup according to HTML5 spec.
	 * Defaults to `false` which means that markup is formatted as HTML4.
	 */
	public $html5 = false;

	/**
	 * Consume lines for a paragraph
	 *
	 * Allow block elements to break paragraphs
	 */
	protected function consumeParagraph($lines, $current)
	{
		// consume until newline
		$content = [];
		for ($i = $current, $count = count($lines); $i < $count; $i++) {
			$line = $lines[$i];
			if ($this->isParagraphEnd($line)) {
				break;
			}
			$content[] = $line;
		}
		$block = [
			'paragraph',
			'content' => $this->parseInline(implode("\n", $content)),
		];
		return [$block, --$i];
	}

	/**
	 * Checks if the paragraph ends
	 * @param $line
	 * @return bool true if end of paragraph
	 */
	protected function isParagraphEnd($line)
	{
		if (empty($line) ||
			ltrim($line) === '' ||
			$this->identifyHeadline($line) ||
			$this->identifyHr($line) ||
			$this->identifyUl($line) ||
			$this->identifyOl($line) ||
			$this->identifyTable($line) ||
			$this->identifyCode($line) ||
			$this->identifyRawHtml($line))
		{
			return true;
		}
		return false;
	}


	/**
	 * Parses escaped special characters.
	 * Creole uses tilde (~) for the escaping marker.
	 * It should escape the next character whatever it would be.
	 * @marker ~
	 */
	protected function parseEscape($text)
	{
		if (isset($text[1])) {
			return [['text', $text[1]], 2];
		}
		return [['text', $text[0]], 1];
	}

	/**
	 * @inheritdocs
	 *
	 * Parses a newline indicated by two backslashes, and
	 * escape '&', '<', and '>'.
	 */
	protected function renderText($text)
	{
		return str_replace(
			['&', '<', '>', "\\\\"],
			['&amp;', '&lt;', '&gt;', $this->html5 ? "<br>" : "<br />"],
			$text[1]);
	}
}
