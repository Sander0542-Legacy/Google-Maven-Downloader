<?php

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Google Maven Downloader</title>

    <link rel="stylesheet" type="text/css" href="/assets/style/materialize-0.100.2/materialize.min.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/style/materialicons/material-icons.css"/>

    <script async src="/assets/style/materialize-0.100.2/materialize.min.js"></script>
    <script async src="/assets/style/jquery-3.2.1/jquery.min.js"></script>
  </head>
  <body style="grey lighten-1">
    <div class="container">
      <div class="row">
        <div class="col l1"></div>
        <div class="col l10">
<?php

$master = "https://dl.google.com/dl/android/maven2";

if (isset($_GET["group"])) {

  $groupIndex = str_replace(".","/",$_GET["group"]);
  $masterGroup = $master."/".$groupIndex."/group-index.xml";
	
	$xml = simplexml_load_file($masterGroup);

  echo '
            <ul class="collapsible" data-collapsible="accordion">';
  foreach($xml as $tag) {

    $versions = explode(",",$tag["versions"][0]);
    foreach($versions as $version) {
      $latestVersion = $version;
    }

    echo '
              <li>
                <div class="collapsible-header"><i class="material-icons">filter_drama</i>'.$tag->getName().'<span class="new badge blue" data-badge-caption="">'.$latestVersion.'</span></div>
                <div class="collapsible-body"><span>'; 

    foreach($versions as $version) {
      
      $downloadURL = $master."/".$groupIndex."/".$tag->getName()."/".$version."/".$tag->getName()."-".$version.".aar";

      echo '<a target="_blank" href="'.$downloadURL.'">Download version '.$version.' (AAR)</a><br/>';
    }
                
    echo '</span></div>
              </li>';
  }
  echo '
            </ul>';
} else {

  $masterIndex = $master."/master-index.xml";
  $xmlMaster = simplexml_load_file($masterIndex);

  echo '
          <ul class="collapsible" data-collapsible="accordion">';
  foreach($xmlMaster as $tag) {

    echo '
            <li>
              <div onclick="window.location.href=\'/google-maven?group='.$tag->getName().'\'" class="collapsible-header"><i class="material-icons">filter_drama</i>'.$tag->getName().'</div>
            </li>';
  }
  echo '
          </ul>';
}

?>

        </div>
      </div>
    </div>
    <script>
      $(document).ready(function(){
        $('.collapsible').collapsible();
      });
    </script>
  </body>
</html>