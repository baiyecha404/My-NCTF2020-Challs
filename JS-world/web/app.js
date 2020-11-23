const express = require('express');
const app = express();
const bodyParser = require('body-parser');
const cookieParser = require("cookie-parser");
const path = require("path");
const session = require("express-session");
const FileStore = require('session-file-store')(session);
const fs = require('fs');

app.set('views', __dirname + '/views');
app.set('view engine', 'ejs');
app.engine('html', require('ejs').renderFile);
app.use(express.static('public'));
app.use(session({
    name: 'session',
    secret: 'T0pSsssecRet233#@###',
    store: new FileStore({path: path.join(__dirname, "sessions")}),
    resave: false,
    saveUninitialized: false
}));


app.use(bodyParser.urlencoded({ extended: false }));
app.use(cookieParser());


const KEY = process.env.KEY || "r5NmfIzU1uzl6Wp";

const xor = (secretkey,value) => {
    return Array.prototype.slice.call(value).map(  function(chr,index){
        return String.fromCharCode(secretkey[index % secretkey.length].charCodeAt(0) ^ chr.charCodeAt(0))
    }).join('');
}

app.get('/' ,(req,res) => {
    let session=req.session;
    if(session.AccessGranted === undefined){
        session.AccessGranted=true;
    } 
    return res.render('index.html');
})

app.get('/templates', (req,res) => {
    const session = req.session;
    if(session.AccessGranted !== "undefined" && session.AccessGranted === true){
        try{
            let template_path = path.join("templates/", session.id, 'index.html');
            return res.render(template_path);
        }catch(err){
            throw err;
        }
    }else{
        return res.send('Not Accessible Now.');
    }
});


app.post('/create', (req,res) => {
    const session = req.session;
    if(session.AccessGranted !== "undefined" && session.AccessGranted === true){
        try{
            const id = session.id;
            const raw = Buffer.from(req.body.code, 'base64').toString();
            const contents = xor(KEY,raw);
            let template_path = path.join(__dirname , "/views/templates/", id, 'index.html');
            if (!fs.existsSync(path.join(__dirname , "/views/templates/", id))) {
                fs.mkdirSync(path.join(__dirname , "/views/templates/", id));
            }
            fs.writeFileSync(template_path, contents);
            return res.send('done');
        }catch(err){
            throw err;
        }
    }else{
        return res.send('Not Accessible Now.');
    }
});

app.all('*', (req, res) => {
    return res.status(404).send('404 page not found');
});

app.listen(8088, () => console.log('Listening on port 8088'));
