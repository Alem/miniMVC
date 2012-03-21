<?php

/*
 * Share
 *
 * Stores printable social-media sharing HTML snippets such as addThis.
 *
 */

class Share{


	function addThis(){
	$addThis = <<<CODE
<!-- AddThis Button BEGIN -->
	<a href="" class="addthis_button info btn large btn-info btn-large">Share</a>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f3c5fc249ed3d26"></script>
<!-- AddThis Button END -->
CODE;
	
	echo $addThis;
	}

}


?>
