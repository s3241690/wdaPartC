<?php 
	require_once('connect.inc');
	require_once('storedProcedures.inc');
	$pdo = createPDO();
	
	// Get Regions
	$query = 'select region_name from region';
    $result = $pdo->query($query);
	$i = 0;
	while($row = $result->fetch(PDO::FETCH_OBJ))  {
		$regionList[$i] = $row->region_name;
		$i++;
	}
	// Get Grape Varieties
	$query = 'select variety from grape_variety;';
    $result = $pdo->query($query);
	$i = 0;
	while($row = $result->fetch(PDO::FETCH_OBJ))
	{
		$grapeVarietyList[$i] = $row->variety;
		$i++;
	}
	
	// Get Lower Bound of the Wine Production Years
	$query = 'select min(year) as min from wine';
    $result = $pdo->query($query);
	$result = $result->fetch(PDO::FETCH_OBJ);
	$yearMinimum = $result->min;
	
	
	
	// Get Upper Bound of the Wine Production Years
	$query = 'select max(year) as max from wine;';
    $result = $pdo->query($query);
	$result = $result->fetch(PDO::FETCH_OBJ);
	$yearMaximum = $result->max;
	
	// Calculate Production Year Difference 
	$yearDifference = $yearMaximum - $yearMinimum;
	
	
		
	// Collect GET variables	
		/* Collect GET Search Criteria */
if($_GET['wine'] != null)
	$wine = $_GET['wine'];

if($_GET['winery'] != null)
	$winery = $_GET['winery'];

if($_GET['region'] != null)
	$region = $_GET['region'];

if($_GET['grapeVariety'] != null)
	$grapeVariety = $_GET['grapeVariety'];
	
if($_GET['yearLowerBound'] != null)
	$yearLowerBound = $_GET['yearLowerBound'];
	
if($_GET['yearUpperBound'] != null)
	$yearUpperBound = $_GET['yearUpperBound'];
	
if($_GET['minWinesInStock'] != null)
	$minWinesInStock = $_GET['minWinesInStock'];
	
if($_GET['minWinesOrdered'] != null)
	$minWinesOrdered = $_GET['minWinesOrdered'];
	
if($_GET['costLowerBound'] != null)
	$costLowerBound = $_GET['costLowerBound'];
	
if($_GET['costUpperBound'] != null)
	$costUpperBound = $_GET['costUpperBound'];
	
if($_GET['errors'] != null)
	$errors = $_GET['errors'];
?>

<html>
	<head>
		<Title>WDA Assignment 1: Wine Database Search Page</Title>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	
	<body>
	
	<h1>WineStore Database: Search Page</h1>

<?php
	/* Check if there are any error messages to display */
	switch($errors)
	{
	case -1:
		echo '<p class="error">You have attempted to search the database in an unauthorise way, please re-submit:</p>';
		break;
	
	case 1:
		echo '<p class="error">You have entered invalid year requirements, please check them and re-submit:</p>';
		break;
	case 2:
		echo '<p class="error">You have entered invalid cost requirements, please check them and re-submit:</p>';
		break;
	case 3:
		echo '<p class="error">You have entered invalid year requirements, please check them and re-submit:</p>';
		echo '<p class="error">You have entered invalid cost requirements, please check them and re-submit:</p>';
		break;
		
	default:
		echo '<p>Please enter your search requirments below:</p>';
	}
	
?>

		
		
		<!-- Display Search Form-->
		<form action ="processSearch.php" method="GET">
			<fieldset>
				
				<legend>Search Wines</legend>
				
				<div class="row">
					<label class="search">Wine Name:</label>
					<input type="text" value="<?php echo $wine;?>" name="wine" />
				</div>
				
				<div class="row">
				 <label class="search">Winery Name:</label>
				 <input type="text" value="<?php echo $winery;?>" name="winery" />
				</div>
				<!--Display Region List -->
				<div class="row">
					<label class="search">Region:</label>
					<select name="region">
						<?php
						 foreach($regionList as $regionOption)  {


							if (strcmp($regionOption, $region) == 0)
								echo '<option value="'. $regionOption . '" selected="selected">'.$regionOption.'</option>';
							else
								echo '<option value="'. $regionOption . '">'.$regionOption.'</option>';
						}
						?>
					</select>
				</div>
				
				<!--Display Grape Variety List -->	
				<div class="row">
					<label class="search">Grape Variety:</label>
					<select name="grapeVariety">
						<?php

						 foreach($grapeVarietyList as $grapeVarietyOption){
							if (strcmp($grapeVarietyOption, $grapeVariety) == 0)
								echo '<option value="'. $grapeVarietyOption. '" selected="selected">'. $grapeVarietyOption. '</option>';
							else
								echo '<option value="'. $grapeVarietyOption. '">'. $grapeVarietyOption. '</option>';
						}
						?>
					</select>
				</div>
				
				<!-- Display Year Lower -->
				<div class="row"> 	
					<label class="search">Year Lower Bound:</label>
					<select name="yearLowerBound">
						
						<?php
						for ($ylb = $yearMinimum; $ylb <= $yearMaximum; $ylb++)
						{
							if ($ylb == $yearLowerBound)
								echo '<option value="'.$ylb.'" selected="selected">'.$ylb.'</option>';
							else
								echo '<option value="'.$ylb.'">'.$ylb.'</option>';
						}		
						?>		
					</select>
				</div>
				<!-- Display Year Upper -->
				<div class="row">
					<label class="search">Year Upper Bound:</label>
					<select name="yearUpperBound">
						<?php
						for ($yub = $yearMaximum; $yub >= $yearMinimum; $yub--)
						{
							if ($yub == $yearUpperBound)
								echo '<option value="'.$yub.'" selected="selected">'.$yub.'</option>';
							else
								echo '<option value="'.$yub.'">'.$yub.'</option>';
						}		
						?>		
					</select>
				</div>
				
				<!-- Display Minimum Wines In Stock-->
				<div class="row">
					<label class="search">Minimum Wines in Stock:</label>
					<input type="text" value="<?php echo $minWinesInStock;?>" name="minWinesInStock" />
				</div>
				<!-- Display Minimum Wines Ordered -->
				<div class="row">
					<label class="search">Minimum Wines Ordered:</label>
					<input type="text" value="<?php echo $minWinesOrdered;?>" name="minWinesOrdered" />
				</div>
				
				<!-- Display Cost Range-->
				<div class="row">
					<label class="search">Cost Minimum:</label>
					<input type="text" value="<?php echo $costLowerBound;?>" name="costLowerBound" />
				</div>
				<div class="row">
					<label class="search">Cost Maximum:</label>
					<input type="text" value="<?php echo $costUpperBound;?>" name="costUpperBound" />
				</div>
			
				<div class="row">
					<input type="submit" name="submit" value=" Search " class="search"> 
					<input type="reset">
				</div>
			</fieldset>
			</form>
	
	</body>

</html>

<?php
	// Destroy the PDO
	destroyPDO($pdo);
?>