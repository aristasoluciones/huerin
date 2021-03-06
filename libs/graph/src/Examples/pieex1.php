<?php // content="text/plain; charset=utf-8"
require_once ('../jpgraph.php');
require_once ('../jpgraph_pie.php');

// Some data
$data = array(113,5,160,3,15,10,1);

// Create the Pie Graph.
$graph = new PieGraph(300,200);
$graph->ClearTheme();

$graph->SetFrame(false);
//$graph->Stroke(

// Set A title for the plot
$graph->title->Set("Example 1 Pie plot");
$graph->title->SetFont(FF_DV_SANSSERIF,FS_BOLD,14); 
$graph->title->SetColor("brown");

// Create pie plot
$p1 = new PiePlot($data);
$p1->SetSliceColors(array("#000","blue","yellow","green"));
$p1->ShowBorder(0);
//$p1->SetTheme("earth");

//$p1->value->SetFont(FF_ARIAL,FS_NORMAL,10);
// Set how many pixels each slice should explode
$p1->Explode(array(0,15,15,25,15));


$graph->Add($p1);
$graph->Stroke();

?>


