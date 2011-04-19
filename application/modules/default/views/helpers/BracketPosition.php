<?php
/**
 * Tournament view helpers
 *
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 */

/**
 * Generates the HTML for a single bracket position
 *
 * @package	   Tournament
 * @author     Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Zend_View_Helper_BracketPosition extends Zend_View_Helper_Abstract {

	/**
	 * Generates the HTML for a single bracket position
	 *
	 * @access public
	 * @param  int $position The bracket position to generate (0-indexed)
	 * @return string The HTML for the position
	 */
	public function bracketPosition($position) {
		// no special functions - not the owner of this tournament
		if (Tournament_Auth::getInstance()->id != $this->view->bracket->userId) {
			return $this->getPositionDisplay($position);
		}

		$html = '';

		// remove link - round 2 or greater
		if ($position >= $this->view->bracket->size && !is_null($this->view->bracket->data[$position])) {
			$removeUrl = '/tournaments/remove?id=' . $this->view->bracket->id . '&position=' . $position;
			$html .= '<a href="' . htmlentities($removeUrl) . '">(x)</a>&nbsp;&nbsp;';
		}

		// advance link - any position except the winner
		if ($position < $this->view->bracket->size * 2 - 2 && !is_null($this->view->bracket->data[$position])) {
			$advanceUrl = '/tournaments/advance?id=' . $this->view->bracket->id . '&position=' . $position;
			$html .= '<a href="' . htmlentities($advanceUrl) . '">' . $this->getPositionDisplay($position) . '</a>';
		}
		else {
			$html .= $this->getPositionDisplay($position);
		}

		return $html;
	}

	/**
	 * Gets the display text for a single position
	 *
	 * @access public
	 * @param  int $position The bracket position to get the display text of
	 * @return string The position display text
	 */
	private function getPositionDisplay($position) {
		if (is_null($this->view->bracket->data[$position])) {
			if ($position < $this->view->bracket->size) {
				return '--BYE--';
			}
			else {
				return '--';
			}
		}

		return htmlentities($this->view->bracket->data[$position]);
	}

}
