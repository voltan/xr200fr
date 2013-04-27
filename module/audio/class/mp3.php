<?php
class mp3 {

	/**
	/**
	/**
	/**
	 * @return the $acodec
	 */
	public function getAcodec() {
		return $this->acodec;
	}

	/**
	 * @param field_type $acodec
	 */
	public function setAcodec($acodec) {
		$this->acodec = $acodec;
	}


	public function mp3($ffmpeg, $srcPath, $destPath, $sampleRate = 44100, $bitRate = 128 , $acodec) {
		$this->setAcodec ( $acodec );
	}
		// ffmpeg -i son_origine.avi -vn -ar 44100 -ac 2 -ab 192 -f mp3 son_final.mp3
		exec ( $command );
	
	public function duration($input) {
		$command = $this->ffmpegPath . " -i " . $this->srcPath . "\\" . $this->input . " 2>&1";
      // Get ffmpeg info
		ob_start();
		passthru($command);
		$duration = ob_get_contents();
		ob_end_clean();
		// Select output
		preg_match('/Duration: (.*?),/', $duration, $matches, PREG_OFFSET_CAPTURE, 3);
		// Make time
		$arr = explode(':', $matches[1][0]);
		$duration = ( $arr[0] * 60 * 60 ) + ( $arr[1] * 60 ) + ( $arr[2] );
		return $duration;
	}