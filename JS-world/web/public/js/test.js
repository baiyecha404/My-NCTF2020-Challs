const xor = (secretkey,value) =>{
    return Array.prototype.slice.call(value).map(  function(chr,index){
        return String.fromCharCode(secretkey[index % secretkey.length].charCodeAt(0) ^ chr.charCodeAt(0))
    }).join('');
}
const raw = Buffer.from(`aw0YDgNTb2McCj4QVm9jHwEBHH8QFwVTRSwbDBg6EgQJOgBKRTceDAFVVlw1CgMXHBkXPgRAPAcbSj0cBxsEKxATGUAKNgFbFywSTlJVSBwMKwAMWSAbHjJFQUM2AhAtVDs6GRgJPgcNZUtwFhsdAgJhZUlbNwQNCGF+VA1uUjkVBwEXOwAVQx8fACwRASsRDEU9CkgKHSxeXQFfWVVTFxs7GFJmYxcHATpSWFVbRUFkWUMAAAkAYX5TcBYHASZNYlNYNxYfBVA=`, 'base64').toString();
const contents = xor('Welcome_to_the_show_bring_out_all_the_lights',raw);
//console.log(btoa(xor('Welcometotheshowbringoutallthelights',``)));
console.log(contents);