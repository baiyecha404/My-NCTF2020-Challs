const express = require('express');
const app = express();
const routes = require('./routes');
const bodyParser = require('body-parser');
const cookieParser = require('cookie-parser');
const nunjucks = require('nunjucks');

nunjucks.configure('views', {
    autoescape: true,
    express: app
});

app.use(bodyParser.json());
app.use(cookieParser());
app.set('views','./views');

app.use(routes);

app.all('*', (req, res) => {
    return res.status(404).send('404 page not found');
});

app.listen(8086, () => console.log('Listening on port 8086'));
