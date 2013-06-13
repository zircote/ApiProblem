<?php
namespace ApiProblemTests\Exception;

/**
 * @package     
 * @category    
 * @subcategory 
 */
use ApiProblem\ApiProblem;

/**
 * @package     
 * @category    
 * @subcategory 
 */
class ApiProblemTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ApiProblem
     */
    protected $fixture;
    public function setup()
    {
        $this->fixture = new ApiProblem(
            'http://api-problem.domain.com/some-url.html',
            'It\'s Broken',
            401,
            'some detail',
            'http://domain.com/this-request'
        );
    }
    public function tearDown()
    {
        $this->fixture = null;
    }
    
    public function testSendResponseJson()
    {
        $expected = 
            'Content-Type:application/api-problem+json' 
            . "\r\n" 
            . 'Link: <http://api-problem.domain.com/some-url.html>; rel="http://api-problem.domain.com/some-url.html";'
            . ' title="It\'s Broken"'
            . "\r\n\r\n" 
            . '{"problemType":"http://api-problem.domain.com/some-url.html","title":"It\'s Broken","httpStatus":401,'
            .'"detail":"some detail","problemInstance":"http://domain.com/this-request"}';

        $actual = $this->fixture->sendHTTPResponse();
        $this->assertEquals($expected, $actual);
    }
    
    public function testSendResponseXml()
    {
        $expected = 
            'Content-Type:application/api-problem+xml' 
            . "\r\n" 
            . 'Link: <http://api-problem.domain.com/some-url.html>; rel="http://api-problem.domain.com/some-url.html";'
            . ' title="It\'s Broken"'
            . "\r\n\r\n" 
            . '<?xml version="1.0" encoding="UTF-8"?>'
            . "\n"
            . '<problem xmlns="urn:ietf:draft:nottingham-http-problem"><problemType>http://api-problem.domain.com/some'
            .'-url.html</problemType><title>It\'s Broken</title><httpStatus>401</httpStatus><detail>some detail</detail>'
            .'<problemInstance>http://domain.com/this-request</problemInstance></problem>'
            . "\n";

        $actual = $this->fixture->sendHTTPResponse(ApiProblem::FORMAT_XML);
        $this->assertEquals($expected, $actual);
    }
    
    
    public function testSendResponseExtensionsXml()
    {
        $expected = 
            'Content-Type:application/api-problem+xml' 
            . "\r\n" 
            . 'Link: <http://api-problem.domain.com/some-url.html>; rel="http://api-problem.domain.com/some-url.html";'
            . ' title="It\'s Broken"'
            . "\r\n\r\n" 
            . '<?xml version="1.0" encoding="UTF-8"?>'
            . "\n"
            . '<problem xmlns="urn:ietf:draft:nottingham-http-problem"><problemType>http://api-problem.domain.com/some'
            .'-url.html</problemType><title>It\'s Broken</title><httpStatus>401</httpStatus><detail>some detail</detail>'
            .'<problemInstance>http://domain.com/this-request</problemInstance><ext_test>etx_test_value</ext_test>'
            .'<ext_test_array_i><a>a_d</a><b>b_d</b><c>c_d</c></ext_test_array_i>'
            .'<ext_test_array_ni><i>a</i><i>b</i><i>c</i></ext_test_array_ni>'
            .'</problem>'
            . "\n";

        $this->fixture->setExtensionData('ext_test', 'etx_test_value');
        $this->fixture->setExtensionData('ext_test_array_i', array('a' => 'a_d', 'b' => 'b_d', 'c' => 'c_d'));
        $this->fixture->setExtensionData('ext_test_array_ni', array('a', 'b', 'c'));
        $actual = $this->fixture->sendHTTPResponse(ApiProblem::FORMAT_XML);
        $this->assertEquals($expected, $actual);
    }
    
    public function testSendResponseExtensionsJson()
    {
        $expected = 
            'Content-Type:application/api-problem+json' 
            . "\r\n" 
            . 'Link: <http://api-problem.domain.com/some-url.html>; rel="http://api-problem.domain.com/some-url.html";'
            . ' title="It\'s Broken"'
            . "\r\n\r\n" 
            . '{"problemType":"http://api-problem.domain.com/some-url.html","title":"It\'s Broken","httpStatus":401,'
            .'"detail":"some detail","problemInstance":"http://domain.com/this-request","ext_test":"etx_test_value",'.
            '"ext_test_array_i":{"a":"a_d","b":"b_d","c":"c_d"},"ext_test_array_ni":["a","b","c"]}';

        $this->fixture->setExtensionData('ext_test', 'etx_test_value');
        $this->fixture->setExtensionData('ext_test_array_i', array('a' => 'a_d', 'b' => 'b_d', 'c' => 'c_d'));
        $this->fixture->setExtensionData('ext_test_array_ni', array('a', 'b', 'c'));
        $actual = $this->fixture->sendHTTPResponse(ApiProblem::FORMAT_JSON);
        $this->assertEquals($expected, $actual);
    }
}
