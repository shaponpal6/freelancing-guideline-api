<?php

include './header.php';
require_once './functions.php';
// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

$file = 'data/posts.json';
$jsonString = file_get_contents($file);
$prevData = json_decode($jsonString, true);
// print_r($prevData);




if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $data = [];
    $name = test_input($_POST["name"]);
    $cat = test_input($_POST["cat"]);
    // check if name only contains letters and whitespace
    // if (!preg_match("/^[a-zA-Z0-9-' ]*$/",$name)) {
    //     $nameErr = "Only letters and white space allowed";
    // }
     $data['title'] = $name;
     $id = preg_replace("/[^a-zA-Z0-9]+/", "-", $name);
     $data['id'] = substr(strtolower($id),0, 10);
     $data['cat'] = $cat;
     if(isExist($prevData, 'id', $data['id'])){
         $nameErr = "This Category Exist!!";
     }
     print_r($data);

    if($nameErr === ""){
        $jsonString = file_get_contents($file);
        $prevData = json_decode($jsonString, true);
        $prevData[] = $data;
        $contents = json_encode($prevData);  
        file_put_contents($file, $contents); 
    }
  }
}


?>

<h2>PHP Form Validation Example</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Name: <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <label for="cars">Choose a car:</label>
<br/>
Category: <select id="cars" name="cat">
    <?php
    $file1 = 'data/category.json';
    $jsonString1 = file_get_contents($file1);
    $prevData1 = json_decode($jsonString1, true);
    foreach ($prevData1 as $data){
        echo "<option value=".$data['id'].">". $data['title']. "</option>";
    }
?>

</select>
<br/>
  <input type="submit" name="submit" value="Add">  
</form>

<?php
echo "<ul>";
$jsonString = file_get_contents($file);
$prevData = json_decode($jsonString, true);
foreach ($prevData as $data){
    echo "<li>".$data['id']." : ". $data['title']. " <a href='./contents.php?id=".$data['id']."&cat=".$data['cat']."&title=".$data['title']."'>Add Post</a></li>";
}
echo "</ul>";
include './footer.php';
?>
