const express = require('express');
const app = express();
const bodyParser = require('body-parser');
const path = require('path');
const session = require('express-session')
const FileStore = require('session-file-store')(session);
const randomize = require('randomatic')
const cookieParser = require("cookie-parser");
const routes = require('./routes/index');


app.use(session({
    name: 'session',
    secret: randomize('aA0', 16),
    store: new FileStore({path: path.join(__dirname, "sessions")}),
    resave: false,
    saveUninitialized: false
}))

app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'pug');
app.use(bodyParser.urlencoded({extended: true}));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'static')));
app.use(routes);


app.all('*', (req, res) => {
    return res.status(404).send('404 page not found');
});

app.listen(8084, () => console.log('Listening on port 8084'));


module.exports = app;
