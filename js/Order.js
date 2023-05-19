window.onload() = ordenar();

function ordenar(){
    s=document.querySelector("#idSerial");
    Array.from(s.options).sort(
        (a,b) => a.text.toLowerCase() > b.text.toLowerCase() ? 1: -1
    ).forEach(
        el => s.add(el)
    );
}