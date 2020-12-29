<?php

 include './header.php';
require_once './functions.php';
// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

$file = 'data/'.$_GET["id"].'.json';
$jsonString = file_get_contents($file);
$prevData = json_decode($jsonString, true);
// echo "<pre>";
// print_r($_SERVER);




if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $data = $prevData;
    $prevContent = $prevData['content'] ?? [];
    $name = test_input($_POST["name"]);
    $cat = test_input($_POST["cat"]);
    $type = test_input($_POST["type"]);
    $text = test_input($_POST["content"]);
    $content['type'] = $type;
    if($type !== 'one'){
      $content['type'] = $type;
      $content['text'] = $text;
      // $data['content'] = $content;
      array_push($prevContent, $content);
    }
    $data['content'] = $prevContent;
    // if (!preg_match("/^[a-zA-Z0-9-' ]*$/",$name)) {
    //     $nameErr = "Only letters and white space allowed";
    // }
     $data['title'] = $name;
     $id = preg_replace("/[^a-zA-Z0-9]+/", "-", $name);
     $data['id'] = substr(strtolower($id),0, 7);
     $data['cat'] = $cat;
     $content['cat'] = $cat;
     if(isExist($prevData, 'id', $data['id'])){
         $nameErr = "This Category Exist!!";
     }
    //  print_r($data);

    if($nameErr === ""){
        // $jsonString = file_get_contents($file);
        // $prevData = json_decode($jsonString, true);
        // $prevData['content'][] = $content;
        // $prevData[] = $data;
        
        $contents = json_encode($data);  
        file_put_contents($file, $contents); 
        print_r('Content Added Successfully');
    }else{
      print_r($nameErr);
    }
  }
}


?>


<h2>Add Data</h2>
<div class="container">
  <div class="left">
<p><span class="error"></span></p>
<form method="post" name="one" >  
  <input type="hidden"  name="cat" value="<?php echo $_GET["id"];?>">
  <br/>
  Name: <input type="text" name="name" value="<?php echo $name =='' ? $_GET["title"] : $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
<br/>
Content Type: <select id="cars" name="type">
  <option value="content">Content(p)</option>
  <option value="h1">Title(h1)</option>
  <option value="h2">Sub Title(h2)</option>
  <option value="h4">Bold Text(h4)</option>
  <option value="list">List(ul/li)</option>
  <option value="one">Default</option>
  <option value="image">Image(img)</option>
</select>
<br/>
Content: <textarea name="content" rows="10" cols="100"><?php echo $comment;?></textarea>
<br/>
  <input type="submit" name="submit" value="Add">  
</form>



</div>
  <div class="right">

  <?php
echo '<div class="content">';
$jsonString = file_get_contents($file);
$prevData = json_decode($jsonString, true);
foreach ($prevData['content'] as $data){
    echo "<p>".$data['text']."</p>";
}
echo "</div>";
?>



  </div>
</div>


<?php include './footer.php';
