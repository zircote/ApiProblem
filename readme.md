# ApiProblem


 - [![Master Build Status](https://secure.travis-ci.org/zircote/ApiProblem.png?branch=master)](http://travis-ci.org/zircote/ApiProblem) `master`


`ApiProblem` is an attempt to provide the functionality and problem reporting as defined in 
[Problem Details for HTTP APIs](http://tools.ietf.org/html/draft-nottingham-http-problem). The goal being a simple 
Exception wrapper for PHP that can send the desired response in `JSON` and `XML`.

## Use

#### The `sendHttpResponse` method

The `sendHTTPResponse` has two parameter, both optional `$format` and `$terminate`.
 - `format`: 
  - `ApiProblem::FORMAT_XML`: `application/api-problem+json`
  - `ApiProblem::FORMAT_JSON`: `application/api-problem+xml`
 - `terminate`: `bool` 
  - `true` (default) headers are sent and execution is terminated.
  - `false` the body payload is returned

#### JSON Example

```php
<?php
$apiProblem = new ApiProblem(
    'http://api-problem.domain.com/some-url.html',
    'Bad Request',
    400,
    'some detail',
    'http://domain.com/this-request'
);
$apiProblem->sendHTTPResponse(ApiProblem::FORMAT_JSON);

```

#### Result

```http
HTTP/1.0 400 Bad Request
Access-Control-Allow-Origin: *
Content-Type: application/api-problem+json
Link: <http://api-problem.domain.com/some-url.html>; rel="http://api-problem.domain.com/some-url.html" title="Bad Request"

{
    "problemType": "http://api-problem.domain.com/some-url.html",
    "title": "Bad Request",
    "httpStatus": 400,
    "detail": "some detail",
    "problemInstance": "http://domain.com/this-request"
}

```


#### XML Example

```php
<?php
try {
  throw new ApiProblem(
      'http://api-problem.domain.com/some-url.html',
      'Bad Request',
      400,
      'some detail',
      'http://domain.com/this-request'
  );
} catch (ApiProblem $e) {
  $e->sendHTTPResponse(ApiProblem::FORMAT_XML);
}

```

#### Result

```http
HTTP/1.0 400 Bad Request
Access-Control-Allow-Origin: *
Content-Type: application/api-problem+xml
Link: <http://api-problem.domain.com/some-url.html>; rel="http://api-problem.domain.com/some-url.html"; title="Bad Request"


<?xml version="1.0" encoding="UTF-8"?>
<problem xmlns="urn:ietf:draft:nottingham-http-problem">
    <problemType>http://api-problem.domain.com/some-url.html</problemType>
    <title>Bad Request</title>
    <httpStatus>400</httpStatus>
    <detail>some detail</detail>
    <problemInstance>http://domain.com/this-request</problemInstance>
</problem>

```
