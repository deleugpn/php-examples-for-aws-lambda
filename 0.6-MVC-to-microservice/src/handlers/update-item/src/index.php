
 <?
 /* Copyright 2020 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 
 Permission is hereby granted, free of charge, to any person obtaining a copy of this
 software and associated documentation files (the "Software"), to deal in the Software
 without restriction, including without limitation the rights to use, copy, modify,
 merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 permit persons to whom the Software is furnished to do so.
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. */
 


 use Aws\DynamoDb\Exception\DynamoDbException;


 function index ($event)
 {
    $sdk = new Aws\Sdk([
        'region'   => 'eu-west-1',
        'version'  => 'latest'
    ]);
    $dynamodb = $sdk->createDynamoDb();
    $tableName = $_ENV['SAMPLE_TABLE']; // Get the DynamoDB table name from environment variables
    $id=$event['pathParameters']['id'];
    $params = 
    [
    'TableName' => $tableName,
    'Key'=>array('id'=>array('S'=>$id))
    ];
    $params = [
        'TableName' => $tableName,
        'Key' => array('id'=>array('S'=>$id)),
        'UpdateExpression' => 
            'SET Title = :title',
        'ExpressionAttributeValues'=> array(":title"=>array('S'=>$event['queryStringParameters']['title'])),
        'ReturnValues' => 'UPDATED_NEW'
    ];
    try{
        $result = $dynamodb->updateItem($params);
    }catch(DynamoDbException $e){
        echo 'Unable to update Item: \n';
        echo $e->getMessage().'\n';
    }

    return APIResponse(strval($result));
 }
 
 function APIResponse($body)
 {
     $headers = array("Content-Type"=>"application/json", "Access-Control-Allow-Origin"=>"*", "Access-Control-Allow-Headers"=>"Content-Type" ,"Access-Control-Allow-Methods" =>"OPTIONS,POST");
     return json_encode(array(
         "statusCode"=>200,
         "headers"=>$headers,
         "body"=>$body
     ));
 }
 