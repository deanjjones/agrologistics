<?php
require_once("jpgraph-3.5.0b1\src\jpgraph.php");
require_once("jpgraph-3.5.0b1\src\jpgraph_line.php");

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


            //verify input
            if(empty($chartingData))
            {
                throw new EmptyInputException();
            }
            
            //generate output

            // Create a graph instance
            $graph = new Graph($width, $height);

            // Specify what scale we want to use,
            // int = integer scale for the X-axis
            // int = integer scale for the Y-axis
            $graph->SetScale('intint');
            
            // Setup a title for the graph
            $graph->title->Set($title);

            // Setup titles and X-axis labels
            $graph->xaxis->title->Set($xAxisTitle);

            // Setup Y-axis title
            $graph->yaxis->title->Set($yAxisTitle);

            $ydata = $chartingData;

            // Create the linear plot
            $lineplot = new LinePlot($ydata);

            // Add the plot to the graph
            $graph->Add($lineplot);

            // Display the graph
            $graph->Stroke();

            // Stream the result back as a PNG image
            header("Content-type: image/png");
        }
        catch(EmptyInputException $ex)
        {   
            $this->_response->setHttpResponseCode(422);
            
            $outputData['code']       = '450';
            $outputData['message']    = 'Error: The input was empty';
            
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