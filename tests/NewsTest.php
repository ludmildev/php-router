<?php
use GuzzleHttp\Client;

class NewsTest extends PHPUnit_Framework_TestCase
{
    private $_baseUri = 'http://router.system/';
    
    public function test_GET_without_id_no_content()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);

        $response = $client->request('GET', 'news');
        $body = $response->getBody();
        
        $this->assertEquals('{"success":0,"message":"News Not Found"}', (string)$body);
    }
    public function test_GET_with_id_no_content()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('GET', 'news/1');
        $body = $response->getBody();
        
        $this->assertEquals('{"success":0,"message":"News Not Found"}', (string)$body);
    }
    
    public function test_POST_create_new_news_empty_title()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('POST', '/news',[
            'form_params' => [
                'title' => '',
                'text' => 'test',
                'date' => '2015-10-20 00:00:61'
            ]
        ]);
        
        $body = $response->getBody();
        $this->assertEquals('{"success":0,"message":"Title cannot be empty"}', (string)$body);
    }
    public function test_POST_create_new_news_empty_text()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('POST', '/news', [
            'form_params' => [
                'title' => 'test',
                'text' => '',
                'date' => '2015-10-20 00:00:61'
            ]
        ]);
        $body = $response->getBody();
        $this->assertEquals('{"success":0,"message":"Text cannot be empty"}', (string)$body);
    }
    public function test_POST_create_new_news_empty_date()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('POST', '/news', [
            'form_params' => [
                'title' => 'test',
                'text' => 'test',
                'date' => ''
            ]
        ]);
        $body = $response->getBody();
        $this->assertEquals('{"success":0,"message":"Date cannot be empty"}', (string)$body);
    }
    public function test_POST_create_new_news_invalid_date()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('POST', '/news', [
            'form_params' => [
                'title' => 'test',
                'text' => 'test',
                'date' => '2015-10-20 00:00:61'
            ]
        ]);
        $body = $response->getBody();
        $this->assertEquals('{"success":0,"message":"Invalid date"}', (string)$body);
    }
    public function test_POST_create_new_news_success()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('POST', '/news', [
            'form_params' => [
                'title' => 'test',
                'text' => 'test',
                'date' => '2015-10-20 00:00:00'
            ]
        ]);
        $body = $response->getBody();
        $this->assertEquals('{"success":1,"message":"","id":"1","title":"test","text":"test","date":"2015-10-20 00:00:00"}', (string)$body);
    }
    public function test_PUT_update_news_invalid_id()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('PUT', '/news/2',[
            'form_params' => [
                'title' => 'test1',
                'text' => 'test1',
                'date' => '2015-10-20 00:00:00'
            ]
        ]);
        $body = $response->getBody();
        
        $this->assertEquals('{"success":0,"message":"Invalid news id"}', (string)$body);
    }
    
    public function test_PUT_update_news_empty_title()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('PUT', '/news/1',[
            'form_params' => [
                'title' => '',
                'text' => 'test1',
                'date' => '2015-10-20 00:00:00'
            ]
        ]);
        $body = $response->getBody();
        $this->assertEquals('{"success":0,"message":"Title cannot be empty"}', (string)$body);
    }
    public function test_PUT_update_news_empty_text()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('PUT', '/news/1',[
            'form_params' => [
                'title' => 'test1',
                'text' => '',
                'date' => '2015-10-20 00:00:00'
            ]
        ]);
        $body = $response->getBody();
        $this->assertEquals('{"success":0,"message":"Text cannot be empty"}', (string)$body);
    }
    public function test_PUT_update_news_empty_date()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('PUT', '/news/1',[
            'form_params' => [
                'title' => 'test1',
                'text' => 'test1',
                'date' => ''
            ]
        ]);
        $body = $response->getBody();
        $this->assertEquals('{"success":0,"message":"Date cannot be empty"}', (string)$body);
    }
    public function test_PUT_update_news_invalid_date()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('PUT', '/news/1',[
            'form_params' => [
                'title' => 'test1',
                'text' => 'test1',
                'date' => '2015-10-20 00:00:61'
            ]
        ]);
        $body = $response->getBody();
        $this->assertEquals('{"success":0,"message":"Invalid date"}', (string)$body);
    }
    public function test_PUT_update_news_success()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('PUT', '/news/1',[
            'form_params' => [
                'title' => 'test1',
                'text' => 'test1',
                'date' => '2015-10-20 00:00:02'
            ]
        ]);
        $body = $response->getBody();
        $this->assertEquals('{"success":1,"message":""}', (string)$body);
    }
    public function test_GET_without_id_success()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);

        $response = $client->request('GET', 'news');
        $body = $response->getBody();
        
        $this->assertEquals('{"success":1,"message":"","news":[{"id":"1","title":"test1","date":"2015-10-20 00:00:02","text":"test1"}]}', (string)$body);
    }
    
    public function test_DELETE_news_invalid_id()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('DELETE', '/news/2');
        $body = $response->getBody();
        
        $this->assertEquals('{"success":0,"message":"Invalid news id"}', (string)$body);
    }
    public function test_DELETE_news_success()
    {
        $client = new Client([
            'base_uri' => $this->_baseUri
        ]);
        
        $response = $client->request('DELETE', '/news/1');
        $body = $response->getBody();
        $this->assertEquals('{"success":1,"message":""}', (string)$body);
        $client->request('GET', '/my_super_secure_clean_database_url/secure_pass');
    }
}