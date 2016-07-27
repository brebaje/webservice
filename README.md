# Webservice

A coding challenge implementation of Location Search

## Description

The project is to implement a web service in PHP that provides json search results from the Google Places API for location based queries. The service returns json results from a get request submitted through the url. It is not necessary to format the json, just display it as raw json. The implementation should be extensible to handle additional queries in the future. It should handle connection problems to the Google Places API gracefully.

- Use an object oriented approach
- Do not use an existing framework

Please provide some sample working url calls to your application to be run on localhost.

1. Find businesses in an area.

Given a query such as “burritos in Berlin” or “ramen in Tokyo” returns a list of establishment names and results. For example,

```
{
"results" : [
      {
         "address" : "1-9-1 Marunouchi, Chiyoda, Tokyo, Japan",
         "name" : "Mutsumiya"
      }],
...
}
```

2. Address autocompletion.

Return possible address predictions for input. Example inputs include, ‘Schlesische Strasse 27C’, ‘Paris’, ‘Gandalf’.

## Implementation

A brief explanation about the relevant files included in the repository:

- **index.php** - a simple web page for testing the web service, includes example url calls and an input for easy testing requests
- **webservice.php** - a simple php file that receives the requests and creates a googlePlaces object that deals with them
- **webservice_interface.php** - an interface definition for the web service
- **google_places.php** - a php class implementation of the web service interface that communicates with the Google Places API

Note that a valid API key for google places should be provided in webservice.php (line 8)