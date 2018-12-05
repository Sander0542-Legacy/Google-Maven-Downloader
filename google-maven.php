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
          <br/>
<?php

$master = "https://dl.google.com/dl/android/maven2";

if (isset($_GET["group"]) && isset($_GET["lib"]) && isset($_GET["version"])) {

  $groupIndex = str_replace(".","/",$_GET["group"]);
  $libIndex = $_GET["lib"];
  $versionIndex = $_GET["version"];
  
  $downloadAAR = $master."/".$groupIndex."/".$libIndex."/".$versionIndex."/".$libIndex."-".$versionIndex.".aar";
  $downloadJAR = $master."/".$groupIndex."/".$libIndex."/".$versionIndex."/".$libIndex."-".$versionIndex.".jar";
  $downloadPOM = $master."/".$groupIndex."/".$libIndex."/".$versionIndex."/".$libIndex."-".$versionIndex.".pom";

  $AARExists = @fopen($downloadAAR, "r");
  $JARExists = @fopen($downloadJAR, "r");
  $POMExists = @fopen($downloadPOM, "r");

  echo '
          <ul class="collapsible" data-collapsible="expandable">
            <li>
              <div class="collapsible-header active"><i class="material-icons">filter_drama</i>'.$libIndex.'<span class="new badge blue" data-badge-caption="">'.$versionIndex.'</span></div>
              <div class="collapsible-body">
                <div class="row">

                  <div'; if ($AARExists) { echo ' onclick="window.location.href=\''.$downloadAAR.'\'"'; }  echo ' class="col l4'; if ($AARExists) { echo ' clickable'; } echo '">
                    <div class="card hoverable '; if ($AARExists) { echo 'green'; } else { echo 'red'; } echo ' lighten-1">
                      <div class="card-content center">
                        <h5 class="white-text">Download AAR</h5>
                      </div>
                    </div>
                  </div>
                  <div'; if ($JARExists) { echo ' onclick="window.location.href=\''.$downloadJAR.'\'"'; }  echo ' class="col l4'; if ($JARExists) { echo ' clickable'; } echo '">
                    <div class="card hoverable '; if ($JARExists) { echo 'green'; } else { echo 'red'; } echo ' lighten-1">
                      <div class="card-content center">
                        <h5 class="white-text">Download JAR</h5>
                      </div>
                    </div>
                  </div>
                  <div'; if ($POMExists) { echo ' onclick="window.location.href=\''.$downloadPOM.'\'"'; }  echo ' class="col l4'; if ($POMExists) { echo ' clickable'; } echo '">
                    <div class="card hoverable '; if ($POMExists) { echo 'green'; } else { echo 'red'; } echo ' lighten-1">
                      <div class="card-content center">
                        <h5 class="white-text">Download POM</h5>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </li>';

  if ($POMExists) {

    $xml = simplexml_load_file($downloadPOM);

    foreach($xml as $tag) {
      if ($tag->getName() == "dependencies") {
        echo '
            <li>
              <div class="collapsible-header active"><i class="material-icons">filter_drama</i>POM Dependencies</div>
              <div class="collapsible-body">
                <div class="row">';
        foreach($tag as $dependency) {
          $groupIndex = str_replace(".","/",$dependency->groupId);
          $onclickURL = '/google-maven?group='.$groupIndex.'&lib='.$dependency->artifactId.'&version='.$dependency->version;
          echo '
                  <div onclick="window.location.href=\''.$onclickURL.'\'" class=" col l3 clickable">
                    <div class="card hoverable grey lighten-4">
                      <div class="card-content center">
                        <h5>'.$dependency->artifactId.'</h5>
                      </div>
                    </div>
                  </div>';
        }
        echo '
              </div>
            </li>';
      }
    }
  }

  echo '
          </ul>';

} elseif (isset($_GET["group"]) && isset($_GET["lib"])) {

  $groupIndex = str_replace(".","/",$_GET["group"]);
  $masterGroup = $master."/".$groupIndex."/group-index.xml";
	
  $xml = simplexml_load_file($masterGroup);

  echo '
            <ul class="collapsible" data-collapsible="accordion">';
  foreach($xml as $tag) {

    if ($tag->getName() == $_GET["lib"]) {

      $versions = explode(",",$tag["versions"][0]);
      foreach($versions as $version) {
        $latestVersion = $version;
      }

      echo '
                <li>
                  <div class="collapsible-header active"><i class="material-icons">filter_drama</i>'.$tag->getName().'<span class="new badge blue" data-badge-caption="">'.$latestVersion.'</span></div>
                  <div class="collapsible-body">
                    <div class="row">'; 

      foreach($versions as $version) {
        
        $downloadURL = $master."/".$groupIndex."/".$tag->getName()."/".$version."/".$tag->getName()."-".$version.".aar";

        echo '
                      <div onclick="window.location.href=\'/google-maven?group='.$_GET["group"].'&lib='.$tag->getName().'&version='.$version.'\'" class="col l3 clickable">
                        <div class="card hoverable grey lighten-4">
                          <div class="card-content center">
                            <h5 class="" target="_blank" href="'.$downloadURL.'">'.$version.'</h5>
                          </div>
                        </div>
                      </div>';
      }
                  
      echo '
                    </div>
                  </div>
                </li>';
    }
  }
  echo '
            </ul>';
} elseif (isset($_GET["group"])) {

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
                <div onclick="window.location.href=\'/google-maven?group='.$_GET["group"].'&lib='.$tag->getName().'\'" class="collapsible-header"><i class="material-icons">filter_drama</i>'.$tag->getName().'<span class="new badge blue" data-badge-caption="">'.$latestVersion.'</span></div>
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
