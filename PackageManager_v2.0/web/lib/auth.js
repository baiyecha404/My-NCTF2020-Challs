const JWT = require('./JWT');

module.exports = async (req, res, next) => {
    if (req.url.includes('debug')){
        return res.status(403).send('Sorry. debug is disabled');
    }
    else {
        let token = req.cookies['auth'];
        let user;
        if (token) {
            try {
            let decoded = await JWT.decode(token);
            user = decoded.username;
            if (user === "admin"){
                    next();
            }
            else {
                return res.status(403).send('Sorry.Only admin can modify package');
            }
            } catch (e) {
                return res.status(500).send('Internal server error');
            }
        }
    }
}
