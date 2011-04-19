<?php
/**
 * Tournament data models
 *
 * @package	   Tournament_Model
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 * @link	   http://jimyi.com
 */

/**
 * Represents a single tournament bracket's data
 *
 * @package	   Tournament_Model
 * @author	   Jim Yi
 * @copyright  Copyright (c) 2010 Jim Yi
 */
class Tournament_Model_Bracket_Data {

	/**
	 * @var int The size of the bracket (4, 8, 16, 32, etc)
	 */
	private $size;

	/**
	 * @var boolean Whether or not to include the seeds of each entrant
	 */
	private $showSeeds;

	/**
	 * @var array The actual bracket data
	 */
	private $bracket;

	/**
	 * Create the data helper
	 *
	 * @access public
	 * @param  array $bracket An array of bracket data
	 */
	public function __construct($bracket = NULL) {
		if ($bracket !== NULL) {
			$this->bracket = $bracket;
		}
	}

	/**
	 * Fill the bracket with the initial array of entrants
	 *
	 * @access public
	 * @param  array $bracket An array of entrants
	 * @param  boolean $randomize Whether or not to randomize seeds
	 */
	public function create($bracket, $randomize = FALSE) {
		// shuffle if desired
		if ((bool) $randomize) {
			shuffle($bracket);
		}
		// remove empty entrants
		foreach ($bracket as $index => &$entrant) {
			$entrant = trim($entrant);
			if (empty($entrant)) {
				unset($bracket[$index]);
			}
		}
		// make sure nobody tries to sneak more than 32 entrants in
		$bracket = array_slice($bracket, 0, 32);

		// minimum bracket size
		$this->size = 4;
		// determine the actual size of the bracket
		while ($this->size < count($bracket)) {
			$this->size = $this->size * 2;
		}

		$this->bracket = $this->fill($bracket);
	}

	/**
	 * Fill open slots in the bracket with NULLs (byes)
	 * and fill placeholder positions for future rounds
	 *
	 * @access protected
	 * @param  array $bracket An array of entrants
	 * @return array The filled bracket
	 */
	protected function fill($bracket) {
		$fill = count($bracket);
		while ($fill < $this->size * 2 - 1) {
			$bracket[$fill] = NULL;
			$fill++;
		}
		return $bracket;
	}

	/**
	 * Get the size of the bracket
	 *
	 * @access public
	 * @return int The size of the bracket
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * Set the size of the bracket
	 *
	 * @access public
	 * @param  int $size The size of the bracket
	 */
	public function setSize($size) {
		$this->size = $size;
	}

	/**
	 * Get the bracket data
	 *
	 * @access public
	 * @return array The bracket data
	 */
	public function getBracket() {
		return $this->bracket;
	}

	/**
	 * Get the bracket data for display
	 *
	 * @access public
	 * @return array The bracket data
	 */
	public function getBracketDisplay() {
		$bracket = $this->bracket;
		if ($this->getShowSeeds()) {
			// TODO: manipulate $bracket
		}
		return $bracket;
	}

	/**
	 * Get whether or not seeds are shown for each entrant
	 *
	 * @access public
	 * @return boolean
	 */
	public function getShowSeeds() {
		return (bool) $this->showSeeds;
	}

	/**
	 * Show the seeds for each entrant
	 *
	 * @access public
	 * @param  boolean $showSeesd
	 */
	public function setShowSeeds($showSeeds) {
		$this->showSeeds = (bool) $showSeeds;
	}


	// the fun stuff begins - actions within the tournament

	/**
	 * Advance a position in the bracket
	 *
	 * @access public
	 * @param  int $position The bracket index to advance
	 */
	public function advance($position) {
		if ($this->bracket[$position] === NULL) return;

		$opponentPosition = $this->getOpponent($position);
		$nextPosition = $this->getNextPosition($position);

		// can't advance the winner
		if ($nextPosition == $position) {
			return;
		}

		// can't advance if we don't even have an opponent yet
		if (is_null($this->bracket[$opponentPosition]) && $opponentPosition >= $this->getSize()) {
			return;
		}

		// remove future positions and update bracket only if we are changing something
		if ($this->bracket[$nextPosition] != $this->bracket[$position]) {
			$this->remove($nextPosition);
			$this->bracket[$nextPosition] = $this->bracket[$position];
		}
	}

	/**
	 * Remove a position along with all future dependent positions
	 *
	 * @access public
	 * @param  int $position The bracket index to remove
	 */
	public function remove($position) {
		// can't remove from the first round
		if ($position < $this->getSize()) {
			return;
		}

		$this->bracket[$position] = NULL;
		// recursively remove future positions
		if ($this->getNextPosition($position) != $position) {
			$this->remove($this->getNextPosition($position));
		}
	}

	/**
	 * Determine the next position for a specified position
	 *
	 * @access protected
	 * @param  int $position The position
	 * @return int The next position
	 */
	protected function getNextPosition($position) {
		if ($position == $this->getSize() * 2 - 2) {
			// the winner can not advance
			return $position;
		}
		$currentOffset = $this->getRoundOffset($this->getRound($position));
		$nextOffset = $this->getRoundOffset($this->getRound($position) + 1);
		$nextOffset += min(array($position - $currentOffset, $this->getOpponent($position) - $currentOffset));
		return $nextOffset;
	}

	/**
	 * Get the opponent of a position
	 *
	 * @access protected
	 * @param  int $position The position
	 * @return int The position's opponent
	 */
	protected function getOpponent($position) {
		$lowerLimit = 0;
		$roundSize = $this->getSize();
		$upperLimit = $this->getSize();
		while ($upperLimit <= $position) {
			$roundSize /= 2;
			$lowerLimit = $upperLimit;
			$upperLimit += $roundSize;
		}
		$opponent = $upperLimit - ($position - $lowerLimit) - 1;
		return $opponent;
	}

	/**
	 * Determine what round a position is in
	 *
	 * @access protected
	 * @param  int $position The position
	 * @return int The round that $position is in
	 */
	protected function getRound($position) {
		$roundSize = $this->getSize();
		$upperLimit = $this->getSize();
		$round = 1;
		while ($upperLimit <= $position) {
			$roundSize /= 2;
			$upperLimit += $roundSize;
			$round++;
		}
		return $round;
	}

	/**
	 * Get the size of the specified round
	 *
	 * @access protected
	 * @param  int $round The round
	 * @return int The size of the round
	 */
	protected function getRoundSize($round) {
		$roundSize = $this->getSize();
		for ($i = 1; $i < $round; $i++) {
			$roundSize /= 2;
		}
		return $roundSize;
	}

	/**
	 * Get the starting offset of the specified round
	 *
	 * @access protected
	 * @param  int $round The round
	 * @return int The offset of the round
	 */
	protected function getRoundOffset($round) {
		$offset = 0;
		for ($i = 2; $i <= $round; $i++) {
			$offset += $this->getRoundSize($i - 1);
		}
		return $offset;
	}

}
