var flag = {"description":"flag_is_here","flag":"NCTF{S0_Actually_P0l1ution_AND_child_pr0cess_l3@ds_t0_RCE!!!}"}
db.flag.drop();
db.flag.insert(flag);
db.createUser({
    user: "admin",
    pwd:  "admin",
    roles: [ { role: "read", db: "flag", collection:"flag" }]
});
