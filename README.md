# Waveform generator
The waveform generator consumes the raw output from an audio silence detection filter. 
These files are stored in folder `\assets`, as I don't think it's appropriate to send such text 
files in a POST request to the API. Maybe they can be stored on a cloud server and downloaded when necessary.

With that scenario in mind, this project has 1 endpoint, that tells which file to be consumed.
After the application read the files and process the information, it return a response in JSON format.

## Dependencies
- Composer 2.4.1
- PHP 8.0

## Getting started
- Pull the project
- Start a server: 
```
php -S localhost:8080
```
- Open Postman and hit: 
```
localhost:8080/waveforms/audio_file_123
```
For other files, you need to add a new audio silence raw data with the following format:
```
assets
|-- <audio_name>
|---- customer_channel
|---- user_channel
```
Then hit it in Postman with the <audio_name>
## Run the tests
Run the following command in terminal 
- ```./vendor/bin/phpunit tests```

## Notes
- I'm noticing user and customer talk overlap for a few seconds sometimes in the sample raw data. 
That may cause some wrong numbers.