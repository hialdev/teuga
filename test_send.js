const http = require('http');
require('dotenv').config();

const host = process.env.host || 'localhost';
const port = process.env.port || 8000;

const json = {
    phone: '+6289699037668',
    message: 'mengirimkan pesan dari whatsapp api',
    attachment: "C:\Users\mnura\Downloads\image.png",
    filename: 'Noer Alif Image.png'
};
const data = JSON.stringify(json);

console.log("sending to " + json.phone + ' -> "' + json.message + '" ...');
console.log();

const options = {
  hostname: host,
  port: port,
  path: '/api/send-message',
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Content-Length': data.length,
  },
};

const req = http.request(options, (res) => {
  let responseData = '';
  let statusCode  = res.statusCode;

  res.on('data', (chunk) => {
    responseData += chunk;
  });

  res.on('end', () => {
    console.log('Response:', responseData);
  });
});

req.on('error', (error) => {

  console.error('Error:', error.message || "Server Offline.");
});

req.write(data);
req.end();

/*
const https = require('https');

// Same data and options as the previous example

const req = https.request(options, (res) => {
  let responseData = '';

  res.on('data', (chunk) => {
    responseData += chunk;
  });

  res.on('end', () => {
    console.log('Response:', responseData);
  });
});

req.on('error', (error) => {
  console.error('Error:', error);
});

req.write(data);
req.end();
*/

console.log();
