module.exports={
    isObject(obj){
        return typeof obj === 'object' || typeof obj === 'function';
    },
    replicate(a, b){
        var attrs=Object.keys(b);
        attrs.forEach( (key) => {
         if (this.isObject(a[key]) && this.isObject(b[key])) {
                this.replicate(a[key], b[key]);
            } else {
                a[key] = b[key];
            }  
        });
        return a;
    }
}
