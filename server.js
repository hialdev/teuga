const { Client, LocalAuth, MessageMedia } = require('whatsapp-web.js');
const qrcode = require('qrcode');
const qrterminal = require('qrcode-terminal');
const express = require('express');
const fs = require('fs');
const app = express();
const {phoneNumberFormatter} = require('./helpers/formatter');

require('dotenv').config();

const port = process.env.PORT || 8000;

const SSLCertificate = process.env.SSLCertificate || '';
const SSLCertificateKey = process.env.SSLCertificateKey || '';
const SSLCertificateAuthor = process.env.SSLCertificateAuthor || '';

var server;
var ishttps = false;

if (SSLCertificate != '' && fs.existsSync(SSLCertificate)){
    const options = {
        key: fs.readFileSync(SSLCertificateKey),
        cert: fs.readFileSync(SSLCertificate),
        ca: fs.readFileSync(SSLCertificateAuthor),
    };

    const https = require('https');
    server = https.createServer(options, app);

    ishttps = true;
}else{
    const http = require('http');
    server = http.createServer(app);
}

const { Server } = require("socket.io");
const io = new Server(server, {
    cors: {
        //methods: ['GET', 'POST'],
        origin: process.env.CORS || "*",
    }
});

const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-accelerated-2d-canvas',
            '--no-first-run',
            '--no-zygote',
            '--disable-gpu'],
    },
    //webVersionCache: { type: 'remote', remotePath: 'https://w.tes123.id/wa/index.html', }
});

var numOfclient = 0;
var clientIsReady = false;
var waNumber = "";

client.once('ready', () => {
    clientIsReady = true;
    waNumber = client.info.wid.user;
});

client.on('qr', (qr) => {
    console.info('QR Data received : '+ qr);
    qrterminal.generate(qr, {small: true});
});

app.use(express.json());
app.use(express.urlencoded({extended: true}));
app.get('/', (request, response) => {
    response.status(200).json({
        message: 'welcome',
    });
})

app.post('/api/send-message', (request, response) => {
    // phone.substring(1) + "@c.us"
    // phone = 62xxxxxxx
    const phone = phoneNumberFormatter(request.body.phone);
    const textmessage = request.body.message;
    const attachment = request.body.attachment;

    let options = undefined;
    let message = textmessage;

    if (attachment && fs.existsSync(attachment)){
        const filename = request.body.filename;
        //console.log("contains file attachment");
        message = MessageMedia.fromFilePath(attachment);

        if (filename){
            message.filename = filename;
        }

        if (textmessage){
            options = { caption: textmessage };
        }
    }

    console.info("sending message : " + JSON.stringify(request.body));

    if (clientIsReady){
        client.sendMessage(phone, message, options)
            .then(result => {
                console.info("- sending SUCCESS");
                response.status(200).json({
                    status: true,
                    message: 'success',
                    data: result,
                });
            })
            .catch(error => {
                console.error("- sending FAILED");
                response.status(200).json({
                    status: false,
                    message: 'error',
                    data: error,
                });
            });
    }else{
        console.error("- wa client not ready");
        response.status(200).json({
            status: false,
            message: 'wa client not ready',
            data: null,
        });
    }
});

io.on('connection', function(socket){
    console.info("new client connected => " + socket.client.conn.server.clientsCount + " client(s)");

    if (clientIsReady){
        socket.emit('ready', waNumber);
    }

    client.on('qr', (qr) => {
        console.info('QR Data received : '+ qr);
        qrcode.toDataURL(qr, (err, url) => {
            socket.emit('qr', url);
        })
    });

    client.on('authenticated', () => {
        socket.emit('authenticated');
        console.info('WA Client authenticated.');
    });

    client.on('ready', () => {
        clientIsReady = true;
        waNumber = client.info.wid.user;
        let msg = 'WA Client is ready! on '+waNumber;
        socket.emit('ready', waNumber);
        console.info(msg);
    });

    socket.on('logout', async () => {
        console.info("WA client Logging out ...");
        if (clientIsReady) {
            await client.logout();
            console.info("WA client Logged Out.");
            clientIsReady = false;
            waNumber = "";
            socket.emit('logout_success'); // Kirim event ke frontend
            client.initialize(); // Inisialisasi ulang client
        }
    });

    socket.on('disconnect', function() {
        numOfclient--;
        console.info("client disconnected => " + socket.client.conn.server.clientsCount + " client(s)");
    });


});

// Start your client
client.initialize();

server.listen(port, () => {
    let msg = "app running on port "+port;
    if (ishttps) msg += " with SSL";
    console.log(msg);
});
