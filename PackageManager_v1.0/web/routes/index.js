const express = require('express');
const router = express.Router();
const { fork } = require('child_process');
const package = require('../package.json');
const rep = require('../lib/rep');
const JWT = require('../lib/JWT');
const AuthMiddlerware = require('../lib/auth');

router.get('/', (req,res) => {
    return res.render('index.html');
});

router.get('/api/package', async(req, res) => {
    let token = req.cookies['auth'];
    if( token == undefined ){
        user={ username : "guest" };
        token=await JWT.sign(user);
        res.cookie('auth', token, { httpOnly: true })
    }
    return res.render('index.html',{ author : package.author , description : package.description });
});

router.post('/api/package',AuthMiddlerware,(req,res)=>{
    let newpackage={};
    newpackage=rep.replicate(newpackage,req.body);
    return res.render('index.html',{ author : newpackage.author , description : newpackage.description }); 
});

//only for self debugging
router.get('/debug/:command', AuthMiddlerware ,(req,res) =>{
    let command= req.params.command;
    if(command){
        if (command == 'cwd') {
            let proc = fork('./checkcwd.js', [], {
                 stdio: ['ignore', 'pipe', 'pipe', 'ipc']
            });
            proc.stderr.pipe(res);
            proc.stdout.pipe(res);
            return;
        } 
        return res.send('Invalid command');
    }
    else{
        return res.end('No command specified.');
    }

});


module.exports=router;
