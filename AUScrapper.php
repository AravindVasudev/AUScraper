/****************************************************************************
* AUScrapper
*
* An ultra-cool script that returns a JSON of Anna University Semester Result for the given register number.
*
* INPUT
*  - The Register Number should be passed as a query string inside `regno` parameter.
*
* OUTPUT
*  - A JSON with subject code as property and grade as value on success, else a property error.
 ***************************************************************************/

 <?php
  $resultJSON = array();

  //Fetches the contents of result page for a given register number into $content
  @$url    = "http://aucoe.annauniv.edu/cgi-bin/result/cgrade.pl?regno=" . $_GET["regno"];
  $content = @file_get_contents($url);

  //If the returned page contains "is wrong", then the register number is not valid, hence returns invalid.
  //(Since the page returns response code 200 for all request, this is a quick hack to do this)
  if(strpos($content, "is wrong!")) {
    echo '{"error": "Invalid Register Number"}';
  }
  else {
    //Scraps the result table table out of the page.
    //Since the returned page did not have any class or id, we cut the table out.
    //A more efficient way to do this would be to select `$(tr[bgcolor="#fffaea"])` and iterate to the result's innerHTML. I'm using this to make it work without dependencies.
    $content     = strip_tags($content, "<table><tbody><tr><td>");
    $table_begin = strpos($content, '<table width="500" border="1" align="center">');
    $content     = substr($content, $table_begin);
    $content     = str_replace(substr($content, strpos($content, "</table>")), "", $content);

    //New DOM is constructed from the table and all the `tr` tags are selected.
    $doc  = new DOMDocument('4.0', 'UTF-8');
    $doc->loadHTML($content);
    $rows = $doc->getElementsByTagName("tr");

    //loops through the rows and extracts the subject code and grade into an array
    //Use `$result = trim($fields->item(4)->nodeValue);` to get the PASS/FAIL value from the table
    foreach($rows as $row) {
      $fields = $row->childNodes;

      $subject = trim($fields->item(0)->nodeValue);
      $grade = trim($fields->item(2)->nodeValue);

      $resultJSON[$subject] = $grade;
    }

    //Removes the table heading from the array
    array_shift($resultJSON);
    echo json_encode($resultJSON);
  }
?>
