<?php

include './header.php';
require_once './functions.php';
// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

$file = 'data/category.json';
$jsonString = file_get_contents($file);
$prevData = json_decode($jsonString, true);
// print_r($prevData);




if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $data = [];
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z0-9-' ]*$/",$name)) {
        $nameErr = "Only letters and white space allowed";
    }
     $data['title'] = $name;
     $id = preg_replace("/[^a-zA-Z0-9]+/", "-", $name);
     $data['id'] = substr(strtolower($id),0, 10);
     if(isExist($prevData, 'id', $data['id'])){
         $nameErr = "This Category Exist!!";
     }

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
  <input type="submit" name="submit" value="Submit">  
</form>

<?php
echo "<ul>";
$jsonString = file_get_contents($file);
$prevData = json_decode($jsonString, true);
foreach ($prevData as $data){
    echo "<li>".$data['id']." : ". $data['title']. "</li>";
}
echo "</ul>";
// echo "<br>";
include './footer.php';
?>

