// mongo mongodb/flag init-test.js
db.flag.drop();
db.flag.insert(flag);
db.createUser({
    user: "admin",
    pwd:  "admin",
    roles: [ { role: "read", db: "flag", collection:"flag" }]
});
