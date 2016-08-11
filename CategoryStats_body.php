<?php
/*
 *
 * ====================== CategoryStats_body.php ===============================
 * Revision Information
 *   Changed: $LastChangedDate$
 *   Revision: $LastChangedRevision$
 *   Last Update By: $Author$
 */
 
/*       1         2         3         4         5         6         7         8
12345678901234567890123456798012345678901234567890123456789012346579801234567890
*/
class CategoryStats extends SpecialPage
{
	function CategoryStats() {
		parent::__construct("CategoryStats");
	}

	function execute( $par ) {
		global $wgRequest, $wgOut;

		$this->setHeaders();

	  // Handle whether the form was posted or not
    if ($wgRequest->wasPosted()){
      $category = $_POST["catsel"];
      $this->processForm($category);
    }else{
      $this->displayForm();
    }

		# Get request data from, e.g.
		$param = $wgRequest->getText('param');


		# Output
		# $wgOut->addHTML( $output );
	}

  function displayForm($category = null){
    global $wgOut, $wgScript;

    $htmlstr = XML::openElement('b')
      . 'This page will display hit statistics for the 20 most-viewed pages in a selected category'
      . XML::closeElement('b');

    $selected = false;

    // Open the Database
    $dbr = wfGetDB(DB_SLAVE);

    // Select all categories where there are at least one _real_ page.
    $query = 'select cat_title from ' . $dbr->tableName('category') .
      ' where cat_pages-cat_subcats > 0 order by cat_title asc';

    $res = $dbr->query($query);

    // Get the dropdown box for the category
    $htmlstr .= XML::openElement('form',
      array(
        'method' => 'post',
       )
      );

      $htmlstr .= XML::element('p', null, 'Select the desired category');
      $htmlstr .= XML::openElement('select',
        array(
          'name' => 'catsel',
          'onchange' => 'this.form.submit()'
          )
       );

      while($row = $dbr->fetchObject($res)){
        if ($category === $row->cat_title) {
          $selected = true;
        }
        $title = $row->cat_title;
        $htmlstr .= XML::option(str_replace('_', ' ', $title), $title, $selected, null );
        $selected = false;
      }

      $htmlstr .= XML::closeElement('select');
      //$htmlstr .= XML::submitButton('Submit');
      $htmlstr .= XML::closeElement('form');

    $dbr->freeResult($res);

    $wgOut->addHTML($htmlstr);


  }

  function processForm($category){
    global $wgOut;
    $htmlstr = '<br>';
    $count = 0;
    $sum = 0;
    $pageloc = $_SERVER['SCRIPT_NAME'];

    $this->displayForm($category);

    // Open the Database
    $dbr = wfGetDB(DB_SLAVE);
    $res = $dbr->select(
        [ 'hc' => 'hit_counter', 'page', 'categorylinks' ],
        [ 'page_title', 'page_counter' ],
        [ 'cl_to' => $category, 'page_namespace' => 0,
          'hc.page_id = page.page_id' ],
        __METHOD__, [ 'ORDER BY' => [ 'page_counter desc' ] ],
        [ 'categorylinks' => [ 'LEFT JOIN', [ 'cl_from = page_id' ] ] ]
    );

    // Calulate the sum of all hits
    while($row = $dbr->fetchObject($res)){
      $sum += $row->page_counter;
    }
    $res->rewind();

    $htmlstr .= XML::openElement('table',
      array(
        'cellpadding' => '5',
        'border' => '1'
        )
      )
      . XML::openElement('tr')
      . XML::openElement('th')
      . XML::closeElement('th')
      . XML::openElement('th')
      . 'CATEGORY: '
      . XML::openElement('a', array('href' => $pageloc .'?title=Category:'. $category))
      . str_replace('_', ' ', $category)
      . XML::closeElement('a')
      . XML::closeElement('th')
      . XML::openElement('th')
      . "Hits: $sum"
      . XML::closeElement('th')
      . XML::closeElement('tr');

    while($row = $dbr->fetchobject($res)){
      $count++;
      if (($count % 2) == 0) {
        $bgcolor = '#ffffff';
      }else{
        $bgcolor = '#edf4f9';
      }

      $htmlstr .= XML::openElement('tr', null)
        . XML::openElement('td', array('align' => 'center', 'bgcolor' => $bgcolor))
        . $count .'.'
        . XML::closeElement('td')
        . XML::openElement('td', array('bgcolor' => $bgcolor))
        . XML::openElement('a', array('href' => $pageloc .'?title='. $row->page_title))
        . str_replace('_', ' ', $row->page_title)
        . XML::closeElement('a')
        . XML::closeElement('td')
        . XML::openElement('td', array('align' => 'center', 'bgcolor' => $bgcolor))
        . $row->page_counter
        . XML::closeElement('td')
        . XML::closeElement('tr');
    }

    $htmlstr .= XML::closeElement('table');
    $dbr->freeResult($res);

    $wgOut->addHTML($htmlstr);


  }

}
