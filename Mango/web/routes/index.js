const express = require('express');
const router = express.Router();
const MongoClient = require('mongodb').MongoClient;
const FLAG="second part of flag is : _squeezy_2333}";

const url = "mongodb://mongodb:27017/";
var dbo;

MongoClient.connect(url, { useUnifiedTopology: true },function (err, db) {
    if (err) throw err;
    dbo = db.db("mangousers");
});


router.get('/',(req,res)=>{
    var user;
    var flag;
    var session = req.session;
    if(session){
        user=session.username;
        if(typeof session.isAdmin == "boolean" && session.isAdmin){
            flag=FLAG;
        }
    }
    res.render('index', { user: user,flag: flag});
});


router.get('/signin',(req,res)=>{
    var user;
    var session=req.session;
    if(session){
        user=session.username;
    }
    res.render('signin', { user: user });
});

router.post('/signin', (req, res)=> {
    var username = req.body.username;
    var password = req.body.password;
    if (!username || !password) {
      return res.render('signin', { error: "Missing parameter" });
    }
    var user = { username: username, password: password};
    dbo.collection("users").findOne(user, function (err, result) {
      if (err) {
        try {
          throw err;
        } catch (error) {
          return res.render('signin', { error: error });
        }
      } else {
        if (result) {
          req.session.username=result.username;
          if(result.role=='admin'){
            req.session.isAdmin=true;
          }
          return res.redirect('/');
        } else {
          return res.render('signin', { error: "Invalid credentials" });
        }
      }
    });
  });


router.get('/signup',(req,res)=>{
    var user;
    var session=req.session;
    if(session){
        user=session.username;
    }
    res.render('signup', { user: user });
});

router.post('/signup',(req, res)=> {
    var username = req.body.username;
    var password = req.body.password;
    var password2 = req.body.password2;
    if (!username || !password || !password2) {
      return res.render('signup', { error: "Missing parameter" });
    }
    if (password != password2) {
      return res.render('signup', { error: "Passwords do not match" });
    }
    var user = { username: username, password: password ,role:'guest'};
    dbo.collection("users").insertOne(user, function (err, result) {
      if (err) {
        try {
          throw err;
        } catch (error) {
          return res.render('signup', { error: error });
        }
      } else {
        return res.redirect('/signin');
      }
    });
  });

router.get('/logout', (req, res)=> {
    res.clearCookie("session");
    res.redirect('/signin');
});

module.exports = router;


