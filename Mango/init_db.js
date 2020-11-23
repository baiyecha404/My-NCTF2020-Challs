// mongo  127.0.0.1/mangousers init_db.js
var admin = {"username":"admin","password":"B$ngo!_The_first_part_of_flag_is:NCTF{ezpz_mongo","role":"admin"}

db.users.drop();
db.users.createIndex({username: 1},{unique:true});
db.users.insert(admin);
