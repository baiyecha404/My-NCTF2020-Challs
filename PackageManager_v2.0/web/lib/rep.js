module.exports={
    isObject(obj){
        return typeof obj === 'object' || typeof obj === 'function';
    },
    
    sanitizeKey(key){
        return key.match(/[\/\*\'\"\`\<\\\>\-\(\)\[\]\=]|__/g);
    },
    replicate(a, b){
        var attrs=Object.keys(b);
        attrs.forEach( (key) => {
          if(this.sanitizeKey(key)=== null){
            if (this.isObject(a[key]) && this.isObject(b[key])) {
                this.replicate(a[key], b[key]);
            } else {
                a[key] = b[key];
            }
          }
            
        });
        return a;
    }
}
