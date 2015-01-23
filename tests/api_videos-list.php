<?php
/*
 * @author skitsanos
 */

require_once '../src/Vzaar.php';

/**
 * This API call returns a list of the user's active videos along with it's relevant metadata
 */
print_r(Vzaar::getVideoList('vzaar'));
?>