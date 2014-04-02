<?php
require_once("jpgraph-3.5.0b1\src\jpgraph.php");
require_once("jpgraph-3.5.0b1\src\jpgraph_line.php");
require_once("jpgraph-3.5.0b1\src\jpgraph_pie.php");
require_once("jpgraph-3.5.0b1\src\jpgraph_pie3d.php");
require_once("jpgraph-3.5.0b1\src\jpgraph_bar.php");
require_once("jpgraph-3.5.0b1\src\jpgraph_scatter.php");

class Api_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        
    }    
    
    /**
    * Generate Graph
    * @param array $requestData 
    * @return image/png
    */
    public function generateGraphAction()
    {   
        $requestDataRaw = isset($_REQUEST['requestData']) && is_string($_REQUEST['requestData']) ? $_REQUEST['requestData'] : '{}';
        
        $outputData = array(
                                'code' => null, 
                                'data' => null, 
                                'debug' => null, 
                                'data' => null, 
                                'message' => null
                            );
            
        try
        {
            $requestData            = json_decode($requestDataRaw, true);
                    
            //process input
            $title                  = isset($requestData['title']) ? $requestData['title'] : '';
            $chartingData           = isset($requestData['data']) && is_array($requestData['data']) ? $requestData['data'] : array();
            $xAxisTitle             = isset($requestData['xAxisTitle']) ? $requestData['xAxisTitle'] : '';
            $yAxisTitle             = isset($requestData['yAxisTitle']) ? $requestData['yAxisTitle'] : '';
            $width                  = isset($requestData['width']) ? $requestData['width'] : 600;
            $height                 = isset($requestData['height']) ? $requestData['height'] : 200;
            $chartType              = isset($requestData['chartType']) ? $requestData['chartType'] : 'line';


            //verify input
            if(empty($chartingData))
            {
                throw new EmptyInputException();
            }
            
            //generate output

            // Create a graph instance
            switch($chartType)
            {
                case 'line':
                case 'scatter':
                case 'bar':
                    $graph = new Graph($width, $height);
                    
                    // Specify what scale we want to use,
                    // int = integer scale for the X-axis
                    // int = integer scale for the Y-axis
                    $graph->SetScale('intint');

                    // Setup titles and X-axis labels
                    $graph->xaxis->title->Set($xAxisTitle);

                    // Setup Y-axis title
                    $graph->yaxis->title->Set($yAxisTitle);
                    break;
                
                case 'pie':
                    $graph = new PieGraph($width, $height);
                    break;
                
                default:
                    throw new InvalidChartTypeException();
            }
            
            // Setup a title for the graph
            $graph->title->Set($title);
            
            $ydata = $chartingData;

            // Create the linear plot
            switch($chartType)
            {
                case 'line':
                    $graphObject = new LinePlot($ydata);
                    break;
            
                case 'scatter':
//                    $graph->SetScale('linlin'); 
        
                    $graph->SetShadow(); 
                    
                    $graph->title->SetFont(FF_FONT1,FS_BOLD); 

                    $graphObject = new ScatterPlot($ydata); 
                    $graphObject->mark->SetType(MARK_FILLEDCIRCLE); 
                    $graphObject->mark->SetFillColor("red"); 
                    $graphObject->mark->SetWidth(5);       
                    break;
                    
                case 'bar':
//                    $graph->SetScale('linlin'); 
        
                    $graph->SetShadow(); 
                    
                    $graphObject = new BarPlot($ydata); 
                
                    break;
                
                case 'pie':
                    
                    $graphObject = new PiePlot3D($ydata); 
                    
                    break;
                
                default:
                    throw new InvalidChartTypeException();
            }

            // Add the plot to the graph
            $graph->Add($graphObject);

            // Display the graph
            $graph->Stroke();

            // Stream the result back as a PNG image
            header("Content-type: image/png");
        }
        catch(EmptyInputException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '450';
            $outputData['message']    = 'Error: The input data was empty or is not valid';
            
            echo $this->processOutput($outputData);
        }
        catch(InvalidChartTypeException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '450';
            $outputData['message']    = 'Error: The chart type specified is invalid';
            
            echo $this->processOutput($outputData);
        }
        catch(Exception $ex)
        {
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '451';
            $outputData['message']    = 'Error: ' . $ex->getMessage();
            
            echo $this->processOutput($outputData);
        }
    }
    
    private function processOutput($outputData)
    {                
        return json_encode($outputData);
    }
}

class EmptyInputException extends Exception
{
    
}

class InvalidChartTypeException extends Exception
{
    
}